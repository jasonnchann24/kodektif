<?php

namespace Tests\Feature\Http\Controllers\Course;

use App\Models\Category;
use App\Models\Course\Course;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;


    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
    }

    /** @test */
    public function non_admin_user_can_not_access_store_update_delete_course_apis()
    {
        $this->withoutExceptionHandling();
        $admin = $this->createAdminUser();
        $user = $this->createBasicUser();
        $this->actingAs($user);

        $this->json('POST', route('courses.store'))
            ->assertStatus(403);

        $course = Course::factory()
            ->for($admin)
            ->create();

        $this->json('PATCH', route('courses.update', ['course' => $course->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_store_course()
    {
        $admin = $this->createAdminUser();
        $this->createCourse($admin);
    }

    /** @test */
    public function admin_can_update_course()
    {
        $admin = $this->createAdminUser();
        $course = $this->createCourse($admin);

        $update = [
            'title' => 'Updated',
            'categories' => [2]
        ];

        $this->actingAs($admin)
            ->patchJson(route('courses.update', ['course' => $course->id]), $update)
            ->assertStatus(200);

        $this->assertDatabaseHas('courses', ['title' => $update['title']]);
        $this->assertDatabaseHas('category_course', [
            'category_id' => $update['categories'][0],
            'course_id' => $course->id,
        ]);
    }

    /** @test */
    public function admin_can_delete_course()
    {
        $admin = $this->createAdminUser();
        $course = $this->createCourse($admin);

        $this->actingAs($admin)
            ->deleteJson(route('courses.destroy', ['course' => $course->id]))
            ->assertStatus(204);

        $this->assertSoftDeleted('courses', ['id' => $course->id]);
    }

    /** @test */
    public function users_can_list_available_courses()
    {
        $admin = $this->createAdminUser();
        $this->createCourse($admin);
        $this->createCourse($admin);

        $this->getJson(route('courses.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 => [
                        'id',
                        'title',
                        'description',
                        'user',
                        'user_id',
                        'chapter_count',
                        'slug',
                        'categories' => []
                    ]
                ]
            ]);
    }

    private function createCourse(User $admin): Course
    {

        $this->actingas($admin);

        $categories = Category::all();
        $pickedCategories = $categories->where('parent_id', '!=', null)
            ->random(random_int(1, 2))
            ->pluck('id')
            ->toArray();

        $payload = [
            'title' => 'Course One',
            'description' => 'Course one description',
            'slug' => 'course-one',
            'chapter_count' => 5,
            'categories' => $pickedCategories
        ];

        $res = $this->postJson(route('courses.store'), $payload)
            ->assertStatus(201);

        $this->assertDatabaseHas('courses', Arr::except($payload, ['categories']));

        $course = Course::find($res['data']['id']);
        $this->assertTrue($course->categories()->exists());

        return $course;
    }
}
