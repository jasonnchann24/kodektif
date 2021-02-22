<?php

namespace Tests\Feature\Http\Controllers\Post;

use App\Models\Category;
use App\Models\Language;
use App\Models\Post\Post;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
        $this->seed(LanguageSeeder::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_these_post_apis()
    {

        $this->json('POST', route('posts.store'), [])
            ->assertStatus(401);

        $this->json('PATCH', route('posts.update', ['post' => -1]), [])
            ->assertStatus(401);

        $this->json('DELETE', route('posts.destroy', ['post' => -1]))
            ->assertStatus(401);
    }

    /** @test */
    public function public_can_access_these_post_apis()
    {
        $this->json('GET', route('posts.index'))
            ->assertStatus(200);

        $user = $this->createBasicUser();
        $post = Post::factory()->for($user)->create();

        $this->json('GET', route('posts.show', ['post' => $post->id, 'slug' => $post->slug]))
            ->assertStatus(200);
    }

    /** @test */
    public function suspended_user_can_not_access_store_update_delete_posts_apis()
    {
        $user = $this->createBasicUser();
        $post = Post::factory()->for($user)->create();

        $suspendedUser = $this->suspendUser($user);

        $this->actingAs($suspendedUser);

        $this->json('POST', route('posts.store'), [])
            ->assertStatus(403);

        $this->json('PATCH', route('posts.update', ['post' => $post->id]), [])
            ->assertStatus(403);

        $this->json('DELETE', route('posts.destroy', ['post' => $post->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function public_can_list_all_posts()
    {
        $user = $this->createBasicUser();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
        for ($i = 0; $i <= 30; $i++) {
            $this->createPost($user);
        }


        $this->json('GET', route('posts.index'))
            ->assertStatus(200)
            ->assertJsonStructure(
                [
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

    /** @test */
    public function public_can_show_specific_post()
    {
        $user = $this->createBasicUser();
        $post = $this->createPost($user);

        $this->json('GET', route('posts.show', ['post' => $post->id, 'slug' => $post->slug]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => $post->title,
                'description' => $post->description
            ]);
    }

    /** @test */
    public function user_can_create_new_posts()
    {
        $user = $this->createBasicUser();
        $this->actingAs($user);

        $this->createPost($user);
    }

    /** @test */
    public function user_can_update_own_post()
    {
        $this->withoutExceptionHandling();

        $user = $this->createBasicUser();

        $this->actingAs($user);

        $post = $this->createPost($user);

        $payload = [
            'title' => 'updated post',
            'categories' => [1]
        ];

        $this->json('PATCH', route('posts.update', ['post' => $post->id]), $payload)
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $payload['title']]);

        $this->assertDatabaseHas('posts', [
            'title' => $payload['title']
        ]);

        $this->assertDatabaseHas('category_post', [
            'category_id' => $payload['categories'],
            'post_id' => $post->id
        ]);
    }

    /** @test */
    public function user_can_delete_own_post()
    {
        $user = $this->createBasicUser();
        $post = $this->createPost($user);

        $this->json('DELETE', route('posts.destroy', ['post' => $post->id]))
            ->assertStatus(204);

        $this->assertSoftDeleted('posts', [
            'id' => $post->id
        ]);
    }

    /** @test */
    public function user_can_not_update_or_delete_others_post()
    {
        $userOne = $this->createBasicUser();
        $userTwo = $this->createBasicUser();

        $postedByUserOne = $this->createPost($userOne);

        $payload = [
            'title' => 'Oops'
        ];

        $this->actingAs($userTwo);

        $this->json('PATCH', route('posts.update', ['post' => $postedByUserOne->id]))
            ->assertStatus(403);
        $this->assertDatabaseMissing('posts', $payload);

        $this->json('DELETE', route('posts.destroy', ['post' => $postedByUserOne->id]))
            ->assertStatus(403);
        $this->assertDatabaseHas('posts', [
            'title' => $postedByUserOne->title,
            'description' => $postedByUserOne->description
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
