<?php

namespace Tests\Feature\Http\Controllers\Post\PostComment;

use App\Models\Post\Post;
use App\Models\Post\PostComment\PostComment;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\Post\PostSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class PostCommentControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    protected $posts;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UserProfileSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            LanguageSeeder::class,
            PostSeeder::class
        ]);

        $this->posts = Post::all();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_these_post_comment_apis()
    {
        $this->postJson(route('post-comments.store'))
            ->assertStatus(401);

        $this->deleteJson(route('post-comments.destroy', ['post_comment' => -1]))
            ->assertStatus(401);
    }

    /** @test */
    public function suspended_user_can_not_access_these_post_comment_apis()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $postComment = $this->createComment($user, $post);

        $this->suspendUser($user);

        $this->actingAs($user);
        $this->postJson(route('post-comments.store'))
            ->assertStatus(403);

        $this->deleteJson(route(
            'post-comments.destroy',
            ['post_comment' => $postComment->id]
        ))->assertStatus(403);
    }

    /** @test */
    public function user_can_post_comment()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $this->createComment($user, $post);
    }

    /** @test */
    public function user_can_delete_own_comment()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $postComment = $this->createComment($user, $post);

        $this->actingas($user)
            ->deleteJson(route('post-comments.destroy', [
                'post_comment' => $postComment->id
            ]))
            ->assertStatus(204);

        $this->assertSoftDeleted('post_comments', [
            'id' => $postComment->id
        ]);
    }

    /** @test */
    public function user_can_not_delete_others_comment()
    {
        $userOne = $this->createBasicUser();
        $userTwo = $this->createBasicUser();
        $post = $this->getOnePost();

        $postComment = $this->createComment($userOne, $post);

        $this->actingAs($userTwo)
            ->deleteJson(route('post-comments.destroy', [
                'post_comment' => $postComment->id
            ]))->assertStatus(403);

        $this->assertDatabaseHas('post_comments', ['id' => $postComment->id]);
    }


    protected function getOnePost(): Post
    {
        $post = $this->posts->random(1)->first();

        return $post;
    }

    protected function createComment(User $user, Post $post): PostComment
    {
        $this->actingAs($user);

        $payload = [
            'post_id' => $post->id,
            'body' => 'Hello, this is my comment.',
            'mentions' => json_encode([1, 2])
        ];
        $response = $this->postJson(route('post-comments.store'), $payload)
            ->assertStatus(201)
            ->assertJsonFragment(
                [
                    'user_id' => $user->id,
                    'post_id' => $post->id
                ]
            );

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas('post_comments', Arr::except($payload, ['mentions']));

        $postComment = PostComment::findOrFail($response['id']);
        return $postComment;
    }
}
