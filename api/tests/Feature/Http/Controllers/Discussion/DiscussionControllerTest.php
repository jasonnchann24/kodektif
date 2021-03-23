<?php

namespace Tests\Feature\Http\Controllers\Discussion;

use App\Models\Category;
use App\Models\Discussion\Discussion;
use App\Models\Language;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class DiscussionControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
        $this->seed(LanguageSeeder::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_these_discussion_apis()
    {

        $this->json('POST', route('discussions.store'), [])
            ->assertStatus(401);

        $this->json('PATCH', route('discussions.update', ['discussion' => -1]), [])
            ->assertStatus(401);

        $this->json('DELETE', route('discussions.destroy', ['discussion' => -1]))
            ->assertStatus(401);
    }

    /** @test */
    public function public_can_access_these_discussion_apis()
    {
        $this->json('GET', route('discussions.index'))
            ->assertStatus(200);

        $user = $this->createBasicUser();
        $discussion = Discussion::factory()->for($user)->create();

        $this->json('GET', route('discussions.show', [
            'discussion' => $discussion->id,
            'slug' => $discussion->slug
        ]))->assertStatus(200);
    }

    /** @test */
    public function suspended_user_can_not_access_store_update_delete_discussion_apis()
    {
        $user = $this->createBasicUser();
        $discussion = Discussion::factory()->for($user)->create();

        $suspendedUser = $this->suspendUser($user);

        $this->actingAs($suspendedUser);

        $this->json('POST', route('discussions.store'), [])
            ->assertStatus(403);

        $this->json('PATCH', route('discussions.update', ['discussion' => $discussion->id]), [])
            ->assertStatus(403);

        $this->json('DELETE', route('discussions.destroy', ['discussion' => $discussion->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function public_can_list_all_discussion()
    {
        $user = $this->createBasicUser();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
        for ($i = 0; $i <= 30; $i++) {
            $this->createDiscussion($user);
        }


        $this->json('GET', route('discussions.index'))
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        0 => [
                            'id',
                            'title',
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
    public function public_can_show_specific_discussion()
    {
        $user = $this->createBasicUser();
        $discussion = $this->createDiscussion($user);

        $this->json('GET', route('discussions.show', [
            'discussion' => $discussion->id, 'slug' => $discussion->slug
        ]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => $discussion->title,
            ]);
    }

    /** @test */
    public function user_can_create_new_discussions()
    {
        $user = $this->createBasicUser();
        $this->actingAs($user);

        $this->createDiscussion($user);
    }

    /** @test */
    public function user_can_update_own_discussion()
    {
        $this->withoutExceptionHandling();

        $user = $this->createBasicUser();

        $this->actingAs($user);

        $discussion = $this->createDiscussion($user);

        $payload = [
            'title' => 'updated discussion',
            'categories' => [1]
        ];

        $this->json('PATCH', route('discussions.update', ['discussion' => $discussion->id]), $payload)
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $payload['title']]);

        $this->assertDatabaseHas('discussions', [
            'title' => $payload['title']
        ]);

        $this->assertDatabaseHas('category_discussion', [
            'category_id' => $payload['categories'],
            'discussion_id' => $discussion->id
        ]);
    }

    /** @test */
    public function user_can_delete_own_discussion()
    {
        $user = $this->createBasicUser();
        $discussion = $this->createDiscussion($user);

        $this->json('DELETE', route('discussions.destroy', [
            'discussion' => $discussion->id
        ]))->assertStatus(204);

        $this->assertSoftDeleted('discussions', [
            'id' => $discussion->id
        ]);
    }

    /** @test */
    public function user_can_not_update_or_delete_others_discussion()
    {
        $userOne = $this->createBasicUser();
        $userTwo = $this->createBasicUser();

        $postedByUserOne = $this->createDiscussion($userOne);

        $payload = [
            'title' => 'Oops'
        ];

        $this->actingAs($userTwo);


        $this->json('PATCH', route('discussions.update', [
            'discussion' => $postedByUserOne->id
        ]))->assertStatus(403);
        $this->assertDatabaseMissing('discussions', $payload);

        $this->json('DELETE', route('discussions.destroy', [
            'discussion' => $postedByUserOne->id
        ]))->assertStatus(403);

        $this->assertDatabaseHas('discussions', [
            'title' => $postedByUserOne->title,
        ]);
    }

    protected function createDiscussion(User $user): Discussion
    {
        $categories = Category::all();
        $language = Language::all()->random(1)->first();

        $pickedCategories = $categories->where('parent_id', '!=', null)
            ->random(random_int(1, 2))
            ->pluck('id')
            ->toArray();

        $payload = [
            'title' => 'Discussion title',
            'body' => '<h1>Hello</h1>',
            'language_id' => $language->id,
            'categories' => $pickedCategories
        ];

        $res = $this->actingAs($user)
            ->json('POST', route('discussions.store'), $payload)
            ->assertStatus(201);

        $payload['user_id'] = $user->id;
        $this->assertDatabaseHas(
            'discussions',
            Arr::except($payload, ['categories'])
        );

        $discussionCreated = Discussion::findOrFail($res['data']['id']);
        return $discussionCreated;
    }
}
