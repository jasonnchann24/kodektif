<?php

namespace Tests\Feature\Http\Controllers\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    /** @test */
    public function non_admin_user_can_not_access_store_update_article_apis()
    {

        $admin = $this->createAdminUser();
        $user = $this->createBasicUser();
        $this->actingAs($user);

        $this->json('POST', '/api/articles')
            ->assertStatus(403);

        $article = Article::factory()
            ->for($admin)
            ->create();

        $this->json('PATCH', "/api/articles/{$article->id}")
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
            'title' => 'Yes, updated'
        ];

        $this->actingAs($admin)
            ->json('PATCH', "/api/articles/" . $article['id'], $updatePayload)
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $updatePayload['title']]);

        $this->assertDatabaseHas('articles', $updatePayload);
    }

    /** @test */
    public function admin_can_delete_article()
    {
        $admin = $this->createAdminUser();
        $article = $this->createArticle($admin);

        $this->actingAs($admin)
            ->json('DELETE', "/api/articles/" . $article['id'])
            ->assertStatus(204);

        $this->assertSoftDeleted('articles', ['id' => $article['id']]);
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
                "/api/articles/" . $article->id,
                $payload
            )
            ->assertStatus(403);

        $this->assertDatabaseMissing('articles', $payload);
    }

    /** @test */
    public function all_can_show_article()
    {
        $admin = $this->createAdminUser();
        $article = $this->createArticle($admin);

        Auth::logout();

        $this->json('GET', "/api/articles/{$article['id']}/{$article['slug']}")
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    'title' => $article['title'],
                    'description' => $article['description']
                ]
            );
    }

    /** @test */
    public function can_list_paginated_articles()
    {
        $admin = $this->createAdminUser();
        Article::factory()->count(40)->for($admin)->create();

        Auth::logout();

        $this->json('GET', "/api/articles")
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
                            'author'
                        ]
                    ],
                    'links' => []
                ]
            );
    }

    protected function createArticle($admin)
    {
        $this->actingAs($admin);

        $payload = [
            'title' => 'Article 1',
            'description' => 'This is my article description',
            'body' => 'Hello this my body',
            'language_id' => 1
        ];

        $res = $this->json('POST', "/api/articles")
            ->assertStatus(201);

        $this->assertDatabaseHas('articles', $payload);

        return $res->decodeResponseJson();
    }
}
