<?php

namespace Database\Seeders\Course;

use App\Models\Category;
use App\Models\Course\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
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

        $admin = $admins->random(1)->first();

        $course = Course::factory()
            ->count(random_int(1, 3))
            ->for($admin)
            ->create();

        $courses = Course::all();
        $categories = Category::where('parent_id', '!=', null)->get();

        foreach ($courses as $course) {
            $randomCategories = $categories->random(random_int(1, 2))
                ->pluck('id')
                ->toArray();
            $course->categories()->sync($randomCategories);
        }
    }
}
