<?php

namespace Tests\Feature\Http\Controllers\Post;

use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class MyPostControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
        $this->seed(LanguageSeeder::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_my_posts_apis()
    {
        $this->getJson(route('my-posts'))
            ->assertStatus(401);
    }

    /** @test */
    public function user_can_list_their_own_posts()
    {
        $user = $this->createBasicUser();

        $this->actingAs($user);

        for ($i = 0; $i < 10; $i++) {
            $this->createPost($user);
        }

        $this->getJson(route('my-posts'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 => [
                        'id',
                        'title',
                        'description',
                        'slug',
                        'upvote_count',
                        'downvote_count',
                        'author',
                        'created_at',
                        'has_voted',
                        'categories' => [
                            0 => [
                                'id',
                                'name',
                                'parent_id'
                            ]
                        ]
                    ]
                ],
                'meta' => [],
                'links' => []
            ]);
    }

    protected function createPost(User $user): Post
    {
        $categories = Category::all();
        $language = Language::all()->random(1)->first();

        $pickedCategories = $categories->where('parent_id', '!=', null)
            ->random(random_int(1, 2))
            ->pluck('id')
            ->toArray();

        $payload = [
            'title' => 'Post title',
            'description' => 'Post Description',
            'body' => '<h1>Hello</h1>',
            'language_id' => $language->id,
            'categories' => $pickedCategories
        ];

        $res = $this->actingAs($user)
            ->json('POST', route('posts.store'), $payload)
            ->assertStatus(201);

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas(
            'posts',
            Arr::except($payload, ['categories'])
        );

        $postCreated = Post::findOrFail($res['id']);
        return $postCreated;
    }
}
