<?php

namespace Tests\Feature\Http\Controllers\Post\PostComment;

use App\Models\Post;
use App\Models\Post\PostComment\PostComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $this->postJson(route('post-comments.store'))
            ->assertStatus(403);

        $this->deleteJson(route('post-comments.destroy', ['post_comment' => -1]))
            ->assertStatus(403);
    }

    /** @test */
    public function user_can_post_comment()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $this->createComment($user, $post);
    }

    /** @test */
    public function user_can_not_spam_comment_more_than_1_per_3_minutes()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $this->createComment($user, $post);
        $this->createComment($user, $post)
            ->assertStatus(429);
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
            'mentions' => '[1,2]'
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
        $this->assertDatabaseHas('post_comments', $payload);

        $postComment = PostComment::findOrFail($response['id']);
        return $postComment;
    }
}
