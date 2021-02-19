<?php

namespace Tests\TestTraits;

use App\Models\Role;
use App\Models\User;

trait CreateUserTrait
{

    public function createBasicUser(): User
    {
        $user = User::factory()->create();
        return $user;
    }

    public function createAdminUser(): User
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(
            [
                'id' => 1,
                'name' => 'admin'
            ],
            [
                'name' => 'admin'
            ]
        );
        $user->roles()->sync([$role->id]);

        return $user;
    }
}
