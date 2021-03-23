<?php

namespace Tests\Feature\Http\Controllers\Discussion;

use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionVote;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\Discussion\DiscussionSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class DiscussionVoteControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    protected $discussions;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UserProfileSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            LanguageSeeder::class,
            DiscussionSeeder::class
        ]);

        $this->discussions = Discussion::all();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_discussion_vote_apis()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $this->postJson(route('discussion-votes.store'), [
            'discussion_id' => $discussion->id, 'upvote' => false
        ])->assertStatus(401);

        $discussionVote = DiscussionVote::factory([
            'user_id' => $user->id,
            'discussion_id' => $discussion->id
        ])->create();

        $this->deleteJson(route('discussion-votes.destroy', [
            'discussion_vote' => $discussionVote->id
        ]))->assertStatus(401);
    }

    /** @test */
    public function suspended_user_can_not_access_discussion_vote_apis()
    {
        $this->withoutExceptionHandling();
        $user = $this->createBasicUser();
        $user = $this->suspendUser($user);
        $this->actingAs($user);

        $discussion = $this->getOneDiscussion();

        $this->postJson(route('discussion-votes.store'), [
            'discussion_id' => $discussion->id,
            'upvote' => false
        ])->assertStatus(403);

        $discussionVote = DiscussionVote::factory([
            'user_id' => $user->id,
            'discussion_id' => $discussion->id
        ])->create();

        $this->deleteJson(route('discussion-votes.destroy', [
            'discussion_vote' => $discussionVote->id
        ]))->assertStatus(403);
    }

    /** @test */
    public function user_can_upvote_discussion()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $discussionVote = $this->voteDiscussion($user, $discussion, true);

        $this->assertEquals(true, (bool)$discussionVote->upvote);
    }

    /** @test */
    public function user_can_downvote_discussion()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $discussionVote = $this->voteDiscussion($user, $discussion, false);

        $this->assertEquals(false, (bool)$discussionVote->upvote);
    }

    /** @test */
    public function user_can_not_double_up_vote_discussion()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $this->voteDiscussion($user, $discussion, true);
        $this->voteDiscussion($user, $discussion, true);

        $countVote = DiscussionVote::where('user_id', $user->id)
            ->where('discussion_id', $discussion->id)
            ->count();

        $this->assertEquals(1, $countVote, 'user has double vote.');
    }

    /** @test */
    public function user_can_not_double_down_vote_discussion()
    {
        $user = $this->createBasicUser();
        $post = $this->getOneDiscussion();

        $this->voteDiscussion($user, $post, false);
        $this->voteDiscussion($user, $post, false);

        $countVote = DiscussionVote::where('user_id', $user->id)
            ->where('discussion_id', $post->id)
            ->count();

        $this->assertEquals(1, $countVote, 'user has double vote.');
    }

    /** @test */
    public function user_can_delete_own_vote()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $discussionVote = $this->voteDiscussion($user, $discussion, true);

        $this->actingAs($user)
            ->deleteJson(route('discussion-votes.destroy', [
                'discussion_vote' => $discussionVote->id
            ]))->assertStatus(204);

        $this->assertDatabaseMissing('discussion_votes', [
            'user_id' => $user->id,
            'discussion_id' => $discussion->id
        ]);
    }

    /** @test */
    public function user_can_update_own_vote_direction()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $discussionVote = $this->voteDiscussion($user, $discussion, true);
        $payload = [
            'upvote' => false
        ];
        $this->actingAs($user)
            ->patchJson(
                route('discussion-votes.update', ['discussion_vote' => $discussionVote->id]),
                $payload
            )
            ->assertStatus(200)
            ->assertJsonFragment(['upvote' => false]);

        $payload['user_id'] = $user->id;
        $payload['discussion_id'] = $discussion->id;

        $this->assertDatabaseHas('discussion_votes', $payload);
    }

    /** @test */
    public function user_can_not_update_or_delete_others_vote()
    {
        $user = $this->createBasicUser();
        $userTwo = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $discussionVote = $this->voteDiscussion($user, $discussion, true);

        $this->actingAs($userTwo)
            ->patchJson(route('discussion-votes.update', [
                'discussion_vote' => $discussionVote->id
            ]), ['upvote' => false])->assertStatus(403);

        $this->actingAs($userTwo)
            ->deleteJson(route('discussion-votes.destroy', [
                'discussion_vote' => $discussionVote->id
            ]))->assertStatus(403);

        $this->assertDatabaseHas('discussion_votes', [
            'user_id' => $user->id,
            'discussion_id' => $discussion->id,
            'upvote' => true
        ]);
    }

    /** @test */
    public function discussion_upvote_count_must_be_updated()
    {

        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $postVote = $this->voteDiscussion($user, $discussion, true);

        $discussion = Discussion::find($discussion->id);
        $this->assertEquals(1, $discussion->upvote_count, 'upvote count not updating');
    }

    /** @test */
    public function discussion_downvote_count_must_be_updated()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $discussionVote = $this->voteDiscussion($user, $discussion, false);

        $discussion = Discussion::find($discussion->id);
        $this->assertEquals(1, $discussion->downvote_count, 'downvote count not updating');
    }

    /** @test */
    public function discussion_vote_count_must_be_adjusted_when_update()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $discussionVote = $this->voteDiscussion($user, $discussion, false);

        $this->actingAs($user)
            ->patchJson(route('discussion-votes.update', ['discussion_vote' => $discussionVote->id]), [
                'upvote' => true
            ]);

        $discussion = Discussion::find($discussion->id);
        $this->assertEquals(0, $discussion->downvote_count);
        $this->assertEquals(1, $discussion->upvote_count);
    }

    /** @test */
    public function discussion_upvote_count_must_be_subracted_if_vote_deleted()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $discussionVote = $this->voteDiscussion($user, $discussion, true);

        $this->actingAs($user)
            ->deleteJson(route('discussion-votes.destroy', ['discussion_vote' => $discussionVote->id]));
        $discussion = Discussion::find($discussion->id);

        $this->assertEquals(0, $discussion->upvote_count);
    }

    /** @test */
    public function discussion_downvote_count_must_be_subracted_if_vote_deleted()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $discussionVote = $this->voteDiscussion($user, $discussion, false);

        $this->actingAs($user)
            ->deleteJson(route('discussion-votes.destroy', ['discussion_vote' => $discussionVote->id]));
        $discussion = Discussion::find($discussion->id);

        $this->assertEquals(0, $discussion->downvote_count);
    }

    /** @test */
    public function discussion_must_return_if_current_user_has_voted_or_not()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $this->actingAs($user)
            ->getJson(route('discussions.show', ['discussion' => $discussion->id, 'slug' => $discussion->slug]))
            ->assertJsonFragment([
                'has_voted' => null
            ]);

        $this->assertEquals(null, $discussion->has_voted);
        $discussionVote = $this->voteDiscussion($user, $discussion, true);

        $this->actingAs($user)
            ->getJson(route('discussions.show', ['discussion' => $discussion->id, 'slug' => $discussion->slug]))
            ->assertJsonFragment(
                [
                    'has_voted' => [
                        'id' => $discussionVote->id,
                        'user_id' => $user->id,
                        'discussion_id' => $discussion->id,
                        'upvote' => true,
                    ]
                ]
            );
    }

    protected function getOneDiscussion(): Discussion
    {
        return $this->discussions->random(1)->first();
    }

    protected function voteDiscussion(User $user, Discussion $discussion, bool $vote): DiscussionVote
    {
        $this->actingAs($user);

        $payload = [
            'discussion_id' => $discussion->id,
            'upvote' => $vote
        ];

        $response = $this->postJson(route('discussion-votes.store'), $payload)
            ->assertStatus(201);

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas('discussion_votes', $payload);

        $discussionVote = DiscussionVote::findOrFail($response['id']);
        return $discussionVote;
    }
}
