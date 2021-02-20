<?php

namespace Tests\Feature\Http\Controllers\Article;

use App\Models\Article;
use App\Models\Category;
use App\Models\Language;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    /** @test */
    public function non_admin_user_can_not_access_store_update_delete_article_apis()
    {

        $admin = $this->createAdminUser();
        $user = $this->createBasicUser();
        $this->actingAs($user);

        $this->json('POST', route('articles.store'))
            ->assertStatus(403);

        $article = Article::factory()
            ->for($admin)
            ->create();

        $this->json('PATCH', route('articles.update', ['article' => $article->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_store_article()
    {
        $admin = $this->createAdminUser();
        $this->createArticle($admin);
    }

    /** @test */
    public function admin_can_update_own_article()
    {
        $admin = $this->createAdminUser();
        $article = $this->createArticle($admin);

        $updatePayload = [
            'title' => 'Yes, updated',
            'categories' => [1]
        ];

        $this->actingAs($admin)
            ->json(
                'PATCH',
                route('articles.update', ['article' => $article['id']]),
                $updatePayload
            )
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $updatePayload['title']]);

        $this->assertDatabaseHas('articles', ['title' => $updatePayload['title']]);
        $this->assertDatabaseHas('article_category', [
            'article_id' => $article['id'],
            'category_id' => $updatePayload['categories'][0]
        ]);
    }

    /** @test */
    public function admin_can_not_update_others_article()
    {
        $admin = $this->createAdminUser();
        $adminTwo = $this->createAdminUser();

        $article = Article::factory()->for($adminTwo)->create();

        $payload = ['title' => 'Ooops hacked.'];

        $this->actingAs($admin)
            ->json(
                'PATCH',
                route('articles.update', ['article' => $article->id]),
                $payload
            )
            ->assertStatus(403);

        $this->assertDatabaseMissing('articles', $payload);
    }

    /** @test */
    public function admin_can_delete_article()
    {
        $admin = $this->createAdminUser();
        $article = $this->createArticle($admin);

        $this->actingAs($admin)
            ->json('DELETE', route('articles.destroy', ['article' => $article['id']]))
            ->assertStatus(204);

        $this->assertSoftDeleted('articles', ['id' => $article['id']]);
    }

    /** @test */
    public function admin_can_not_delete_others_article()
    {
        $admin = $this->createAdminUser();
        $adminTwo = $this->createAdminUser();
        $article = $this->createArticle($admin);

        $this->actingAs($adminTwo)
            ->json('DELETE', route('articles.destroy', ['article' => $article['id']]))
            ->assertStatus(403);
    }



    /** @test */
    public function users_can_show_article()
    {
        $admin = $this->createAdminUser();
        $article = $this->createArticle($admin);

        Auth::logout();


        $this->json(
            'GET',
            route(
                'articles.show',
                [
                    'article' => $article['id'],
                    'slug' => $article['slug']
                ]
            )
        )
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    'title' => $article['title'],
                    'description' => $article['description']
                ]
            );
    }

    /** @test */
    public function all_user_can_list_paginated_articles()
    {
        $admin = $this->createAdminUser();

        $this->withoutMiddleware(
            ThrottleRequests::class
        );
        for ($i = 0; $i <= 30; $i++) {
            $this->createArticle($admin);
        }

        Auth::logout();

        $this->json('GET', route('articles.index'))
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        0 => [
                            'id',
                            'title',
                            'description',
                            'slug',
                            'likes_count',
                            'created_at',
                            'author',
                            'categories' => [
                                0 => [
                                    'id',
                                    'name',
                                    'parent_id'
                                ]
                            ]
                        ]
                    ],
                    'links' => [],
                    'meta' => []
                ]
            );
    }

    protected function createArticle($admin)
    {
        $this->actingAs($admin);

        $lang = Language::factory()->create();
        $categories = Category::all();

        if ($categories->count() < 1) {
            $this->seed(CategorySeeder::class);
            $categories = Category::all();
        }

        $pickedCategories = $categories->where('parent_id', '!=', null)
            ->random(random_int(1, 2))
            ->pluck('id')
            ->toArray();

        $payload = [
            'title' => 'Article 1',
            'description' => 'This is my article description',
            'body' => 'Hello this my body',
            'language_id' => $lang->id,
            'categories' => $pickedCategories
        ];

        $res = $this->json('POST', route('articles.store'), $payload)
            ->assertStatus(201);

        $this->assertEquals(Str::slug($payload['title'], '-'), $res['slug']);
        $this->assertDatabaseHas('articles', Arr::except($payload, ['categories']));

        $article = Article::find($res['id']);
        $this->assertTrue($article->categories()->exists());

        return $res->decodeResponseJson();
    }
}
