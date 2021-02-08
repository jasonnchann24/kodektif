<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SuspendControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function non_admin_user_can_not_access_suspend_apis()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json('POST', '/api/suspend-user/1')
            ->assertStatus(403);
        $this->json('POST', '/api/unsuspend-user/1')
            ->assertStatus(403);
    }

    /** @test */
    public function admin_user_can_suspend_user()
    {
        $admin = User::factory()->create();
        $targetUser = User::factory()->create();
        $role = Role::create([
            'name' => 'admin'
        ]);

        $admin->roles()->sync([$role->id]);

        $this->actingAs($admin)
            ->json('POST', '/api/suspend-user/' . $targetUser->id)
            ->assertStatus(200);

        $result = User::find($targetUser->id);
        $this->assertTrue($result->is_suspended == 1);
    }

    /** @test */
    public function admin_user_can_unsuspend_user()
    {
        $admin = User::factory()->create();
        $targetUser = User::factory()->create();
        $role = Role::create([
            'name' => 'admin'
        ]);

        $admin->roles()->sync([$role->id]);

        $this->actingAs($admin)
            ->json('POST', '/api/unsuspend-user/' . $targetUser->id)
            ->assertStatus(200);

        $result = User::find($targetUser->id);
        $this->assertTrue($result->is_suspended == 0);
    }
}
