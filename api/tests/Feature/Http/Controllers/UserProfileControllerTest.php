<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class UserProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, CreateUserTrait;

    /** @test */
    public function non_authenticated_user_can_not_access_these_apis()
    {
        $store = $this->json('POST', route('user-profiles.store'));
        $store->assertStatus(401);

        $put = $this->json('PATCH', route('user-profiles.update', ['user_profile' => -1]));
        $put->assertStatus(401);
    }

    /** @test */
    public function user_can_create_user_profile()
    {
        $user = $this->createBasicUser();
        $data = $this->createUserProfile($user);

        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id]);
    }

    /** @test */
    public function user_can_view_user_profile()
    {
        $user = $this->createBasicUser();
        $data = $this->createUserProfile($user);

        $this->json('GET', route('user-profiles.show', ['user_profile' => $data['id']]))
            ->assertStatus(200)
            ->assertJsonFragment(['user_id' => $user->id]);
    }

    /** @test */
    public function user_can_update_own_profile()
    {
        $user = $this->createBasicUser();
        $profile = $this->createUserProfile($user);
        $updateData = [
            'about' => 'Updated About'
        ];
        $this->actingAs($user)
            ->json('PATCH', route('user-profiles.update', ['user_profile' => $profile['id']]), $updateData)
            ->assertStatus(200)
            ->assertJsonFragment(['about' => $updateData['about']]);

        $this->assertDatabaseHas('user_profiles', $updateData);
    }
    /** @test */
    public function user_can_not_update_other_user_profile()
    {
        $user1 = $this->createBasicUser();
        $user2 = $this->createBasicUser();
        $profile2 = $this->createUserProfile($user2);

        $updateData = [
            'about' => 'You are hacked!'
        ];

        $this->actingAs($user1)
            ->json('PATCH', route('user-profiles.update', ['user_profile' => $profile2['id']]), $updateData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('user_profiles', $updateData);
    }

    /** @test */
    public function suspended_user_can_not_create_profile()
    {
        $user = $this->createBasicUser();
        $user->is_suspended = true;
        $user->save();

        $this->actingAs($user);

        $this->json('POST', route('user-profiles.store'))
            ->assertStatus(403);
    }

    /** @test */
    public function suspended_user_can_not_update_own_profile()
    {
        $user = $this->createBasicUser();
        $profile = $this->createUserProfile($user);
        $user->is_suspended = true;
        $user->save();

        $this->actingAs($user);
        $this->json(
            'PATCH',
            route('user-profiles.update', ['user_profile' => $profile['id']]),
            ['about' => 'still can update']
        )
            ->assertStatus(403);
    }

    protected function createUserProfile($user)
    {
        $data = [
            'country' => $this->faker->countryCode,
            'about' => $this->faker->paragraph(),
            'facebook_link' => $this->faker->url,
            'linkedin_link' => $this->faker->url,
            'github_link' => $this->faker->url,
            'others_link' => $this->faker->url,
        ];

        $res = $this->actingAs($user)
            ->json('POST', route('user-profiles.store'), $data)
            ->assertStatus(201);

        return $res->decodeResponseJson();
    }
}
