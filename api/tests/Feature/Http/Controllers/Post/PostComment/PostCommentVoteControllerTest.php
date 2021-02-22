<?php

namespace Tests\Feature\Http\Controllers\Post\PostComment;

use App\Models\Post\PostComment\PostComment;
use App\Models\Post\PostComment\PostCommentVote;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\Post\PostComment\PostCommentSeeder;
use Database\Seeders\Post\PostSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class PostCommentVoteControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    protected $postComments;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UserProfileSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            LanguageSeeder::class,
            PostSeeder::class,
            PostCommentSeeder::class
        ]);

        $this->postComments = PostComment::all();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_these_comment_vote_apis()
    {
        $this->postJson(route('post-comment-votes.store'), [])
            ->assertStatus(401);

        $this->patchJson(route('post-comment-votes.update', ['post_comment_vote' => -1]), [])
            ->assertStatus(401);

        $this->deleteJson(route('post-comment-votes.destroy', ['post_comment_vote' => 1]))
            ->assertStatus(401);
    }

    /** @test */
    public function suspended_user_can_not_access_these_comment_vote_apis()
    {
        $user = $this->createBasicUser();
        $postComment = $this->getOnePostComment();
        $vote = $this->createPostCommentVote($user, $postComment, true);

        $user = $this->suspendUser($user);

        $this->actingAs($user);
        $this->postJson(route('post-comment-votes.store'), [])
            ->assertStatus(403);

        $this->patchJson(route('post-comment-votes.update', ['post_comment_vote' => $vote->id]), [])
            ->assertStatus(403);

        $this->deleteJson(route('post-comment-votes.destroy', ['post_comment_vote' => $vote->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function user_can_upvote_comment()
    {
        $user = $this->createBasicUser();

        $postComment = $this->getOnePostComment();

        $this->createPostCommentVote($user, $postComment, true);
    }

    /** @test */
    public function user_can_downvote_comment()
    {
        $user = $this->createBasicUser();

        $postComment = $this->getOnePostComment();

        $this->createPostCommentVote($user, $postComment, false);
    }

    /** @test */
    public function user_can_not_double_vote_comment()
    {
        $user = $this->createBasicUser();
        $postComment = $this->getOnePostComment();
        $this->createPostCommentVote($user, $postComment, true);
        $this->createPostCommentVote($user, $postComment, true);

        $commentVote = PostCommentVote::where('user_id', $user->id)
            ->where('post_comment_id', $postComment->id)
            ->count();
        $this->assertEquals(1, $commentVote, 'user can have multiple vote in the same comment');
    }

    /** @test */
    public function user_can_update_own_vote()
    {
        $user = $this->createBasicUser();
        $postComment = $this->getOnePostComment();

        $vote = $this->createPostCommentVote($user, $postComment, true);

        $this->actingAs($user)
            ->patchJson(route('post-comment-votes.update', ['post_comment_vote' => $vote->id]), ['upvote' => false])
            ->assertStatus(200);

        $updated = PostCommentVote::find($vote->id);
        $this->assertEquals(false, (bool)$updated->upvote, 'not updated');
    }

    /** @test */
    public function user_can_not_update_others_vote()
    {
        $user = $this->createBasicUser();
        $userTwo = $this->createBasicUser();

        $postComment = $this->getOnePostComment();
        $vote = $this->createPostCommentVote($user, $postComment, false);

        $this->actingAs($userTwo)
            ->patchJson(route('post-comment-votes.update', ['post_comment_vote' => $vote->id]), ['upvote' => true])
            ->assertStatus(403);

        $target = PostCommentVote::find($vote->id);
        $this->assertEquals(false, (bool)$target->upvote, 'other user can update vote.');
    }

    /** @test */
    public function user_can_delete_own_vote()
    {
        $user = $this->createBasicUser();
        $postComment = $this->getOnePostComment();
        $vote = $this->createPostCommentVote($user, $postComment, false);

        $this->actingAs($user)
            ->deleteJson(route('post-comment-votes.destroy', ['post_comment_vote' => $vote->id]))
            ->assertStatus(204);

        $target = PostCommentVote::where('user_id', $user->id)
            ->where('post_comment_id', $postComment->id)
            ->count();

        $this->assertEquals(0, $target, 'vote not deleted.');
    }

    /** @test */
    public function user_can_not_delete_others_vote()
    {
        $user = $this->createBasicUser();
        $userTwo = $this->createBasicUser();
        $postComment = $this->getOnePostComment();
        $vote = $this->createPostCommentVote($user, $postComment, false);

        $this->actingAs($userTwo)
            ->deleteJson(route('post-comment-votes.destroy', ['post_comment_vote' => $vote->id]))
            ->assertStatus(403);

        $target = PostCommentVote::where('user_id', $user->id)
            ->where('post_comment_id', $postComment->id)
            ->count();

        $this->assertEquals(1, $target, 'vote deleted by other user.');
    }

    /** @test */
    public function post_comment_count_must_be_updated_after_user_vote()
    {
        $user = $this->createBasicUser();
        $postComment = $this->getOnePostComment();

        $vote = $this->createPostCommentVote($user, $postComment, true);

        $updatedComment = PostComment::find($postComment->id);
        $this->assertEquals(1, $updatedComment->upvote_count, 'post comment upvote count not updated');

        $this->actingAs($user)
            ->patchJson(route('post-comment-votes.update', ['post_comment_vote' => $vote->id]), ['upvote' => false]);

        $updatedComment = PostComment::find($postComment->id);
        $this->assertEquals(1, $updatedComment->downvote_count, 'post comment downvote count not updated');
        $this->assertEquals(0, $updatedComment->upvote_count, 'post comment upvote count not subracted after update');
    }

    /** @test */
    public function post_comment_count_must_be_subracted_if_user_delete_vote()
    {
        $user = $this->createBasicUser();
        $postComment = $this->getOnePostComment();

        $vote = $this->createPostCommentVote($user, $postComment, false);

        $this->actingAs($user)
            ->deleteJson(route('post-comment-votes.destroy', ['post_comment_vote' => $vote->id]));

        $updatedComment = PostComment::find($postComment->id);
        $this->assertEquals(0, $updatedComment->downvote_count, 'post comment downvote count not subracted after deletion');
    }

    /** @test */
    public function post_comment_must_return_if_current_user_has_voted_or_not()
    {
        $user = $this->createBasicUser();
        $postComment = $this->getOnePostComment();

        $this->actingAs($user)
            ->getJson(route('post-comments.show', ['post_comment' => $postComment->id]))
            ->assertJsonFragment([
                'has_voted' => null
            ]);

        $this->assertEquals(null, $postComment->has_voted);
        $vote = $this->createPostCommentVote($user, $postComment, true);

        $this->actingAs($user)
            ->getJson(route('post-comments.show', ['post_comment' => $postComment->id]))
            ->assertJsonFragment(
                [
                    'has_voted' => [
                        'id' => $vote->id,
                        'user_id' => $user->id,
                        'post_comment_id' => $postComment->id,
                        'upvote' => true,
                    ]
                ]
            );
    }

    protected function getOnePostComment(): PostComment
    {
        return $this->postComments->random(1)->first();
    }

    protected function createPostCommentVote(User $user, PostComment $postComment, bool $upvote): PostCommentVote
    {
        $this->actingAs($user);

        $payload = [
            'post_comment_id' => $postComment->id,
            'upvote' => $upvote
        ];

        $response = $this->postJson(route('post-comment-votes.store'), $payload)
            ->assertStatus(201);

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas('post_comment_votes', $payload);

        return PostCommentVote::findOrFail($response['id']);
    }
}
