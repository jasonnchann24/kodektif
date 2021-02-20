<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class SuspendControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;
    /** @test */
    public function non_admin_user_can_not_access_suspend_apis()
    {
        $user = $this->createBasicUser();
        $this->actingAs($user);

        $this->json('POST', route('suspend-user.store'))
            ->assertStatus(403);
        $this->json('DELETE', route('suspend-user.destroy', ['id' => -1]))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_user_can_suspend_user()
    {
        $admin = $this->createAdminUser();
        $targetUser = $this->createBasicUser();

        $this->actingAs($admin)
            ->json('POST', route('suspend-user.store'), ['id' => $targetUser->id])
            ->assertStatus(200);

        $result = User::find($targetUser->id);
        $this->assertTrue($result->is_suspended == 1);
    }

    /** @test */
    public function admin_user_can_unsuspend_user()
    {
        $admin = $this->createAdminUser();
        $targetUser = $this->createBasicUser();

        $this->actingAs($admin)
            ->json('DELETE', route('suspend-user.destroy', ['id' => $targetUser->id]))
            ->assertStatus(200);

        $result = User::find($targetUser->id);
        $this->assertTrue($result->is_suspended == 0);
    }
}
