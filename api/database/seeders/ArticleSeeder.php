<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = User::whereHas('roles', function ($role) {
            $role->where(
                'name',
                '=',
                config('constants.role_admin')
            );
        })->get();

        foreach ($admins as $admin) {
            Article::factory()
                ->count(random_int(1, 3))
                ->for($admin)
                ->create();
        }
    }
}
