<?php

namespace Tests\Feature\Http\Controllers\Course;

use App\Models\Course\Chapter;
use App\Models\Course\Course;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\Course\CourseSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class ChapterControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;


    public function setUp(): void
    {
        parent::setUp();

        $this->seed(UserProfileSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(LanguageSeeder::class);
        $this->seed(CourseSeeder::class);
    }

    public function non_admin_can_not_access_these_chapter_apis()
    {
        $admin = $this->createAdminUser();
        $user = $this->createBasicUser();
        $this->actingAs($user);

        $this->postJson(route('chapters.store'))
            ->assertStatus(403);

        $chapter = Chapter::factory()
            ->for($admin)
            ->create();

        $this->patchJson(route('chapters.update', ['chapter' => $chapter->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_store_chapter()
    {
        $admin = $this->createAdminUser();
        $this->createChapter($admin);
    }

    /** @test */
    public function admin_can_update_chapter()
    {
        $admin = $this->createAdminUser();
        $chapter  = $this->createChapter($admin);

        $payload =  [
            'title' => 'updated title',
            'slug' => 'updated-slug'
        ];

        $this->actingAs($admin)
            ->patchJson(route(
                'chapters.update',
                ['chapter' => $chapter->id]
            ), $payload)->assertStatus(200);

        $this->assertDatabaseHas('chapters', $payload);
    }

    /** @test */
    public function admin_can_delete_chapter()
    {
        $admin = $this->createAdminUser();
        $chapter = $this->createChapter($admin);

        $this->actingAs($admin)
            ->deleteJson(route('chapters.destroy', ['chapter' => $chapter->id]))
            ->assertStatus(204);

        $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
    }

    private function createChapter(User $admin)
    {
        $this->withoutExceptionHandling();
        $course = Course::all()->random(1)->first();
        $max = $course->chapters()->max('order');

        $payload = [
            'course_id' => $course->id,
            'title' => 'Chapter Title',
            'order' => ++$max,
            'slug' => 'chapter-title',
        ];

        $res = $this->actingAs($admin)
            ->postJson(route('chapters.store'), $payload)
            ->assertStatus(201);

        $this->assertDatabaseHas(
            'chapters',
            $payload
        );

        $chapter = Chapter::findOrFail($res['data']['id']);
        return $chapter;
    }
}
