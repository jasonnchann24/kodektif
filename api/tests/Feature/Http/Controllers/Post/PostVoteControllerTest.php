<?php

namespace Tests\Feature\Http\Controllers\Post;

use App\Models\Article;
use App\Models\Post;
use App\Models\PostVote;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\Post\PostSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class PostVoteControllerTest extends TestCase
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
    public function non_authenticated_user_can_not_access_post_vote_apis()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $this->postJson(route('post-votes.store'), [
            'post_id' => $post->id, 'upvote' => false
        ])->assertStatus(401);

        $postVote = PostVote::factory([
            'user_id' => $user->id,
            'post_id' => $post->id
        ])->create();

        $this->deleteJson(route('post-votes.destroy', [
            'post_vote' => $postVote->id
        ]))->assertStatus(401);
    }

    /** @test */
    public function suspended_user_can_not_access_post_vote_apis()
    {
        $user = $this->createBasicUser();
        $user = $this->suspendUser($user);
        $this->actingAs($user);

        $post = $this->getOnePost();

        $this->postJson(route('post-votes.store'), [
            'post_id' => $post->id,
            'upvote' => false
        ])->assertStatus(403);

        $postVote = PostVote::factory([
            'user_id' => $user->id,
            'post_id' => $post->id
        ])->create();

        $this->deleteJson(route('post-votes.destroy', [
            'post_vote' => $postVote->id
        ]))->assertStatus(403);
    }

    /** @test */
    public function user_can_upvote_post()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $postVote = $this->votePost($user, $post, true);

        $this->assertEquals(true, (bool)$postVote->upvote);
    }

    /** @test */
    public function user_can_downvote_post()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $postVote = $this->votePost($user, $post, false);

        $this->assertEquals(false, (bool)$postVote->upvote);
    }

    /** @test */
    public function user_can_not_double_up_vote_post()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $this->votePost($user, $post, true);
        $this->votePost($user, $post, true);

        $countVote = PostVote::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->count();

        $this->assertEquals(1, $countVote, 'user has double vote.');
    }

    /** @test */
    public function user_can_not_double_down_vote_post()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $this->votePost($user, $post, false);
        $this->votePost($user, $post, false);

        $countVote = PostVote::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->count();

        $this->assertEquals(1, $countVote, 'user has double vote.');
    }

    /** @test */
    public function user_can_delete_own_vote()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $postVote = $this->votePost($user, $post, true);

        $this->actingAs($user)
            ->deleteJson(route('post-votes.destroy', [
                'post_vote' => $postVote->id
            ]))->assertStatus(204);

        $this->assertDatabaseMissing('post_votes', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }

    /** @test */
    public function user_can_update_own_vote_direction()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $postVote = $this->votePost($user, $post, true);
        $payload = [
            'upvote' => false
        ];
        $this->actingAs($user)
            ->patchJson(
                route('post-votes.update', ['post_vote' => $postVote->id]),
                $payload
            )
            ->assertStatus(200)
            ->assertJsonFragment(['upvote' => false]);

        $payload['user_id'] = $user->id;
        $payload['post_id'] = $post->id;

        $this->assertDatabaseHas('post_votes', $payload);
    }

    /** @test */
    public function user_can_not_update_or_delete_others_vote()
    {
        $user = $this->createBasicUser();
        $userTwo = $this->createBasicUser();
        $post = $this->getOnePost();

        $postVote = $this->votePost($user, $post, true);

        $this->actingAs($userTwo)
            ->patchJson(route('post-votes.update', [
                'post_vote' => $postVote->id
            ]), ['upvote' => false])->assertStatus(403);

        $this->actingAs($userTwo)
            ->deleteJson(route('post-votes.destroy', [
                'post_vote' => $postVote->id
            ]))->assertStatus(403);

        $this->assertDatabaseHas('post_votes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'upvote' => true
        ]);
    }

    /** @test */
    public function post_upvote_count_must_be_updated()
    {

        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $postVote = $this->votePost($user, $post, true);

        $post = Post::find($post->id);
        $this->assertEquals(1, $post->upvote_count, 'upvote count not updating');
    }

    /** @test */
    public function post_downvote_count_must_be_updated()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $postVote = $this->votePost($user, $post, false);

        $post = Post::find($post->id);
        $this->assertEquals(1, $post->downvote_count, 'downvote count not updating');
    }

    /** @test */
    public function post_upvote_count_must_be_subracted_if_vote_deleted()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $postVote = $this->votePost($user, $post, true);

        $this->actingAs($user)
            ->deleteJson(route('post-votes.destroy', ['post_vote' => $postVote->id]));
        $post = Post::find($post->id);

        $this->assertEquals(0, $post->upvote_count);
    }

    /** @test */
    public function post_downvote_count_must_be_subracted_if_vote_deleted()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();
        $postVote = $this->votePost($user, $post, false);

        $this->actingAs($user)
            ->deleteJson(route('post-votes.destroy', ['post_vote' => $postVote->id]));
        $post = Post::find($post->id);

        $this->assertEquals(0, $post->downvote_count);
    }

    /** @test */
    public function post_must_return_if_current_user_has_voted_or_not()
    {
        $user = $this->createBasicUser();
        $post = $this->getOnePost();

        $this->actingAs($user)
            ->getJson(route('posts.show', ['post' => $post->id, 'slug' => $post->slug]))
            ->assertJsonFragment([
                'has_voted' => null
            ]);

        $this->assertEquals(null, $post->has_voted);
        $postVote = $this->votePost($user, $post, true);

        $this->actingAs($user)
            ->getJson(route('posts.show', ['post' => $post->id, 'slug' => $post->slug]))
            ->assertJsonFragment(
                [
                    'has_voted' => [
                        'id' => $postVote->id,
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                        'upvote' => true,
                    ]
                ]
            );
    }

    protected function getOnePost(): Post
    {
        return $this->posts->random(1)->first();
    }

    protected function votePost(User $user, Post $post, bool $vote): PostVote
    {
        $this->actingAs($user);

        $payload = [
            'post_id' => $post->id,
            'upvote' => $vote
        ];

        $response = $this->postJson(route('post-votes.store'), $payload)
            ->assertStatus(201);

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas('post_votes', $payload);

        $postVote = PostVote::findOrFail($response['id']);
        return $postVote;
    }
}
