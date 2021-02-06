<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function non_authenticated_user_can_not_access_these_apis()
    {
        $store = $this->json('POST', '/api/user-profiles');
        $store->assertStatus(401);

        $put = $this->json('PUT', '/api/user-profiles/-1');
        $put->assertStatus(401);

        $delete = $this->json('DELETE', '/api/user-profiles/-1');
        $delete->assertStatus(401);
    }

    /** @test */
    public function user_can_create_user_profile()
    {
        $user = User::factory()->create();
        $data = $this->createUserProfile($user);

        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id]);
        $this->assertEquals(1, $user->profile->count());
    }

    /** @test */
    public function user_can_view_user_profile()
    {
        $user = User::factory()->create();
        $data = $this->createUserProfile($user);

        $this->json('GET', '/api/user-profiles/' . $data['id'])
            ->assertStatus(200)
            ->assertJsonFragment(['user_id' => "$user->id"]);
    }

    /** @test */
    public function user_can_update_own_profile()
    {
        $user = User::factory()->create();
        $profile = $this->createUserProfile($user);
        $updateData = [
            'about' => 'Updated About'
        ];
        $this->actingAs($user)
            ->json('PATCH', '/api/user-profiles/' . $profile['id'], $updateData)
            ->assertStatus(200)
            ->assertJsonFragment(['about' => $updateData['about']]);

        $this->assertDatabaseHas('user_profiles', $updateData);
    }
    /** @test */
    public function user_can_not_update_other_user_profile()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $profile2 = $this->createUserProfile($user2);

        $updateData = [
            'about' => 'You are hacked!'
        ];

        $this->actingAs($user1)
            ->json('PATCH', '/api/user-profiles/' . $profile2['id'], $updateData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('user_profiles', $updateData);
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
            ->json('POST', '/api/user-profiles', $data)
            ->assertStatus(201);

        return $res->decodeResponseJson();
    }
}
