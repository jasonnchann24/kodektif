<?php

namespace Tests\Feature\Http\Controllers\Discussion\DiscussionComment;

use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\Discussion\DiscussionSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class DiscussionCommentControllerTest extends TestCase
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
    public function non_authenticated_user_can_not_access_these_discussion_comment_apis()
    {
        $this->postJson(route('discussion-comments.store'))
            ->assertStatus(401);

        $this->deleteJson(route('discussion-comments.destroy', ['discussion_comment' => -1]))
            ->assertStatus(401);
    }

    /** @test */
    public function suspended_user_can_not_access_these_discussion_comment_apis()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $discussionComment = $this->createComment($user, $discussion);

        $this->suspendUser($user);

        $this->actingAs($user);
        $this->postJson(route('discussion-comments.store'))
            ->assertStatus(403);

        $this->deleteJson(route(
            'discussion-comments.destroy',
            ['discussion_comment' => $discussionComment->id]
        ))->assertStatus(403);
    }

    /** @test */
    public function user_can_post_comment()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();
        $this->createComment($user, $discussion);
    }

    /** @test */
    public function user_can_delete_own_comment()
    {
        $user = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $discussionComment = $this->createComment($user, $discussion);

        $this->actingas($user)
            ->deleteJson(route('discussion-comments.destroy', [
                'discussion_comment' => $discussionComment->id
            ]))
            ->assertStatus(204);

        $this->assertSoftDeleted('discussion_comments', [
            'id' => $discussionComment->id
        ]);
    }

    /** @test */
    public function user_can_not_delete_others_comment()
    {
        $userOne = $this->createBasicUser();
        $userTwo = $this->createBasicUser();
        $discussion = $this->getOneDiscussion();

        $discussionComment = $this->createComment($userOne, $discussion);

        $this->actingAs($userTwo)
            ->deleteJson(route('discussion-comments.destroy', [
                'discussion_comment' => $discussionComment->id
            ]))->assertStatus(403);

        $this->assertDatabaseHas('discussion_comments', [
            'id' => $discussionComment->id
        ]);
    }

    protected function getOneDiscussion(): Discussion
    {
        $discussion = $this->discussions->random(1)->first();

        return $discussion;
    }

    protected function createComment(User $user, Discussion $discussion): DiscussionComment
    {
        $this->actingAs($user);

        $payload = [
            'discussion_id' => $discussion->id,
            'body' => 'Hello, this is my comment.',
            'mentions' => json_encode([1, 2])
        ];
        $response = $this->postJson(route('discussion-comments.store'), $payload)
            ->assertStatus(201)
            ->assertJsonFragment(
                [
                    'user_id' => $user->id,
                    'discussion_id' => $discussion->id
                ]
            );

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas(
            'discussion_comments',
            Arr::except($payload, ['mentions'])
        );

        $discussionComment = DiscussionComment::findOrFail($response['id']);
        return $discussionComment;
    }
}
