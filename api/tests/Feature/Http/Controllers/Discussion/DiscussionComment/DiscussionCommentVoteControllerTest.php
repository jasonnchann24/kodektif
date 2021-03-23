<?php

namespace Tests\Feature\Http\Controllers\Discussion\DiscussionComment;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\DiscussionComment\DiscussionCommentVote;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\Discussion\DiscussionComment\DiscussionCommentSeeder;
use Database\Seeders\Discussion\DiscussionSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class DiscussionCommentVoteControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    protected $discussionComments;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UserProfileSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            LanguageSeeder::class,
            DiscussionSeeder::class,
            DiscussionCommentSeeder::class
        ]);

        $this->discussionComments = DiscussionComment::all();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_these_comment_vote_apis()
    {
        $this->postJson(route('discussion-comment-votes.store'), [])
            ->assertStatus(401);

        $this->patchJson(route('discussion-comment-votes.update', ['discussion_comment_vote' => -1]), [])
            ->assertStatus(401);

        $this->deleteJson(route('discussion-comment-votes.destroy', ['discussion_comment_vote' => 1]))
            ->assertStatus(401);
    }

    /** @test */
    public function suspended_user_can_not_access_these_comment_vote_apis()
    {
        $user = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();
        $vote = $this->createDiscussionCommentVote($user, $discussionComment, true);

        $user = $this->suspendUser($user);

        $this->actingAs($user);
        $this->postJson(route('discussion-comment-votes.store'), [])
            ->assertStatus(403);

        $this->patchJson(route('discussion-comment-votes.update', ['discussion_comment_vote' => $vote->id]), [])
            ->assertStatus(403);

        $this->deleteJson(route('discussion-comment-votes.destroy', ['discussion_comment_vote' => $vote->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function user_can_upvote_comment()
    {
        $user = $this->createBasicUser();

        $discussionComment = $this->getOneDiscussionComment();

        $this->createDiscussionCommentVote($user, $discussionComment, true);
    }

    /** @test */
    public function user_can_downvote_comment()
    {
        $user = $this->createBasicUser();

        $discussionComment = $this->getOneDiscussionComment();

        $this->createDiscussionCommentVote($user, $discussionComment, false);
    }

    /** @test */
    public function user_can_not_double_vote_comment()
    {
        $user = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();
        $this->createDiscussionCommentVote($user, $discussionComment, true);
        $this->createDiscussionCommentVote($user, $discussionComment, true);

        $commentVote = DiscussionCommentVote::where('user_id', $user->id)
            ->where('discussion_comment_id', $discussionComment->id)
            ->count();
        $this->assertEquals(1, $commentVote, 'user can have multiple vote in the same comment');
    }

    /** @test */
    public function user_can_update_own_vote()
    {
        $user = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();

        $vote = $this->createDiscussionCommentVote($user, $discussionComment, true);

        $this->actingAs($user)
            ->patchJson(route('discussion-comment-votes.update', ['discussion_comment_vote' => $vote->id]), ['upvote' => false])
            ->assertStatus(200);

        $updated = DiscussionCommentVote::find($vote->id);
        $this->assertEquals(false, (bool)$updated->upvote, 'not updated');
    }

    /** @test */
    public function user_can_not_update_others_vote()
    {
        $user = $this->createBasicUser();
        $userTwo = $this->createBasicUser();

        $discussionComment = $this->getOneDiscussionComment();
        $vote = $this->createDiscussionCommentVote($user, $discussionComment, false);

        $this->actingAs($userTwo)
            ->patchJson(route('discussion-comment-votes.update', ['discussion_comment_vote' => $vote->id]), ['upvote' => true])
            ->assertStatus(403);

        $target = DiscussionCommentVote::find($vote->id);
        $this->assertEquals(false, (bool)$target->upvote, 'other user can update vote.');
    }

    /** @test */
    public function user_can_delete_own_vote()
    {
        $user = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();
        $vote = $this->createDiscussionCommentVote($user, $discussionComment, false);

        $this->actingAs($user)
            ->deleteJson(route('discussion-comment-votes.destroy', ['discussion_comment_vote' => $vote->id]))
            ->assertStatus(204);

        $target = DiscussionCommentVote::where('user_id', $user->id)
            ->where('discussion_comment_id', $discussionComment->id)
            ->count();

        $this->assertEquals(0, $target, 'vote not deleted.');
    }

    /** @test */
    public function user_can_not_delete_others_vote()
    {
        $user = $this->createBasicUser();
        $userTwo = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();
        $vote = $this->createDiscussionCommentVote($user, $discussionComment, false);

        $this->actingAs($userTwo)
            ->deleteJson(route('discussion-comment-votes.destroy', ['discussion_comment_vote' => $vote->id]))
            ->assertStatus(403);

        $target = DiscussionCommentVote::where('user_id', $user->id)
            ->where('discussion_comment_id', $discussionComment->id)
            ->count();

        $this->assertEquals(1, $target, 'vote deleted by other user.');
    }

    /** @test */
    public function discussion_comment_count_must_be_updated_after_user_vote()
    {
        $user = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();

        $vote = $this->createDiscussionCommentVote($user, $discussionComment, true);

        $updatedComment = DiscussionComment::find($discussionComment->id);
        $this->assertEquals(1, $updatedComment->upvote_count, 'discussion comment upvote count not updated');

        $this->actingAs($user)
            ->patchJson(route('discussion-comment-votes.update', ['discussion_comment_vote' => $vote->id]), ['upvote' => false]);

        $updatedComment = DiscussionComment::find($discussionComment->id);
        $this->assertEquals(1, $updatedComment->downvote_count, 'discussion comment downvote count not updated');
        $this->assertEquals(0, $updatedComment->upvote_count, 'discussion comment upvote count not subracted after update');
    }

    /** @test */
    public function discussion_comment_count_must_be_subracted_if_user_delete_vote()
    {
        $user = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();

        $vote = $this->createDiscussionCommentVote($user, $discussionComment, false);

        $this->actingAs($user)
            ->deleteJson(route('discussion-comment-votes.destroy', ['discussion_comment_vote' => $vote->id]));

        $updatedComment = DiscussionComment::find($discussionComment->id);
        $this->assertEquals(0, $updatedComment->downvote_count, 'discussion comment downvote count not subracted after deletion');
    }

    /** @test */
    public function discussion_comment_must_return_if_current_user_has_voted_or_not()
    {
        $user = $this->createBasicUser();
        $discussionComment = $this->getOneDiscussionComment();

        $this->actingAs($user)
            ->getJson(route('discussion-comments.show', ['discussion_comment' => $discussionComment->id]))
            ->assertJsonFragment([
                'has_voted' => null
            ]);

        $this->assertEquals(null, $discussionComment->has_voted);
        $vote = $this->createDiscussionCommentVote($user, $discussionComment, true);

        $this->actingAs($user)
            ->getJson(route('discussion-comments.show', ['discussion_comment' => $discussionComment->id]))
            ->assertJsonFragment(
                [
                    'has_voted' => [
                        'id' => $vote->id,
                        'user_id' => $user->id,
                        'discussion_comment_id' => $discussionComment->id,
                        'upvote' => true,
                    ]
                ]
            );
    }


    protected function getOneDiscussionComment(): DiscussionComment
    {
        return $this->discussionComments->random(1)->first();
    }

    protected function createDiscussionCommentVote(User $user, DiscussionComment $discussionComment, bool $upvote): DiscussionCommentVote
    {
        $this->actingAs($user);

        $payload = [
            'discussion_comment_id' => $discussionComment->id,
            'upvote' => $upvote
        ];

        $response = $this->postJson(route('discussion-comment-votes.store'), $payload)
            ->assertStatus(201);

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas('discussion_comment_votes', $payload);

        return DiscussionCommentVote::findOrFail($response['id']);
    }
}
