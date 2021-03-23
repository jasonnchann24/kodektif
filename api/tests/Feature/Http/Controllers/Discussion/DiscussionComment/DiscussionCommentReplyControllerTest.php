<?php

namespace Tests\Feature\Http\Controllers\Discussion\DiscussionComment;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\DiscussionComment\DiscussionCommentReply;
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
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class DiscussionCommentReplyControllerTest extends TestCase
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
    public function non_authenticated_user_can_not_access_these_reply_apis()
    {
        $this->postJson($this->setRoute('store'), [])
            ->assertStatus(401);

        $this->deleteJson($this->setRoute('destroy', -1))
            ->assertStatus(401);
    }

    /** @test */
    public function suspended_user_can_not_access_these_reply_apis()
    {
        $user = $this->createBasicUser();

        $comment = $this->getOneComment();
        $reply = DiscussionCommentReply::factory([
            'user_id' => $user->id,
            'discussion_comment_id' => $comment->id,
            'mentions' => json_encode([1, 2])
        ])->create();

        $user = $this->suspendUser($user);
        $this->actingAs($user);
        $this->postJson($this->setRoute('store'), [])
            ->assertStatus(403);
        $this->deleteJson($this->setRoute('destroy', $reply->id))
            ->assertStatus(403);
    }

    /** @test */
    public function user_can_post_new_reply_to_comment()
    {
        $user = $this->createBasicUser();
        $comment = $this->getOneComment();
        $payload = [
            'discussion_comment_id' => $comment->id,
            'body' => "Hello World",
            'mentions' => json_encode([1, 2])
        ];

        $this->createOneReply($user, $payload);
        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas(
            'discussion_comment_replies',
            Arr::except($payload, ['mentions'])
        );
    }

    /** @test */
    public function user_can_delete_own_reply()
    {
        $user = $this->createBasicUser();
        $comment = $this->getOneComment();
        $reply = DiscussionCommentReply::factory([
            'user_id' => $user->id,
            'discussion_comment_id' => $comment->id,
            'mentions' => json_encode([1, 2])
        ])->create();

        $this->actingAs($user);
        $this->deleteJson(
            $this->setRoute('destroy', $reply->id)
        )->assertStatus(204);

        $this->assertSoftDeleted('discussion_comment_replies', ['id' => $reply->id]);
    }

    /** @test */
    public function user_can_not_delete_other_users_reply()
    {
        $user = $this->createBasicUser();
        $userTwo = $this->createBasicUser();
        $comment = $this->getOneComment();
        $reply = DiscussionCommentReply::factory([
            'user_id' => $user->id,
            'discussion_comment_id' => $comment->id,
            'mentions' => json_encode([1, 2])
        ])->create();

        $this->actingAs($userTwo);
        $this->deleteJson(
            $this->setRoute('destroy', $reply->id)
        )->assertStatus(403);

        $findReply = DiscussionCommentReply::find($reply->id);
        $this->assertEquals(1, $findReply->count());
    }

    protected function getOneComment(): DiscussionComment
    {
        return $this->discussionComments->random(1)->first();
    }

    protected function createOneReply(User $user, array $payload): DiscussionCommentReply
    {
        $this->actingAs($user);
        $response = $this->postJson($this->setRoute('store'), $payload)
            ->assertStatus(201);

        return DiscussionCommentReply::find($response['id']);
    }

    protected function setRoute(string $method, int $params = null): string
    {
        if ($method == 'destroy') {
            return route('discussion-comment-replies.' . $method, ['discussion_comment_reply' => $params]);
        }

        return route('discussion-comment-replies.' . $method);
    }
}
