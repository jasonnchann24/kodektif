<?php

namespace Database\Seeders\Course;

use App\Models\Course\Chapter;
use App\Models\Course\Course;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            for ($i = 0; $i < random_int(4, 9); $i++) {
                $max = $course->chapters()->max('order');

                Chapter::factory([
                    'order' => ++$max
                ])->for($course)
                    ->create();

                $course->chapter_count += 1;
                $course->save();
            }
        }
    }
}
