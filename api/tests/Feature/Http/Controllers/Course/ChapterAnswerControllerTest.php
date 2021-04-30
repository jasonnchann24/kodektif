<?php

namespace Tests\Feature\Http\Controllers\Course;

use App\Models\Course\Chapter;
use App\Models\Course\ChapterAnswer;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\Course\ChapterSeeder;
use Database\Seeders\Course\CourseSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class ChapterAnswerControllerTest extends TestCase
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
        $this->seed(ChapterSeeder::class);
    }

    /** @test */
    public function non_authenticated_can_not_access_these_answer_apis()
    {
        $user = $this->createBasicUser();

        $this->postJson(route('chapter-answers.store'))
            ->assertStatus(401);

        $chapter = Chapter::all()->random(1)->first();
        $answer = ChapterAnswer::factory([
            'chapter_id' => $chapter->id
        ])->for($user)
            ->create();

        $this->patchJson(route('chapter-answers.update', ['chapter_answer' => $answer->id]))
            ->assertStatus(401);
    }

    /** @test */
    public function user_can_create_answer()
    {
        $user = $this->createBasicUser();
        $chapter = $this->getOneChapter();
        $this->createAnswer($user, $chapter);
    }

    /** @test */
    public function user_can_only_have_one_answer()
    {
        $user = $this->createBasicUser();
        $chapter = $this->getOneChapter();
        $this->createAnswer($user, $chapter);
        $this->createAnswer($user, $chapter);

        $count = ChapterAnswer::where('user_id', $user->id)->where('chapter_id', $chapter->id)
            ->count();

        $this->assertEquals(1, $count);
    }

    /** @test */
    public function user_can_update_his_own_answer()
    {
        $userOne = $this->createBasicUser();
        $userTwo = $this->createBasicUser();

        $chapter = $this->getOneChapter();

        $answerOne = $this->createAnswer($userOne, $chapter);
        $answerTwo = $this->createAnswer($userTwo, $chapter);

        $payload = ['answer' => 'console.log("test")'];

        $this->actingAs($userOne)
            ->patchJson(route('chapter-answers.update', ['chapter_answer' => $answerTwo->id]), $payload)
            ->assertStatus(403);

        $this->actingAs($userOne)
            ->patchJson(route('chapter-answers.update', ['chapter_answer' => $answerOne->id]), $payload)
            ->assertStatus(200);
    }

    private function getOneChapter(): Chapter
    {
        $chapter = Chapter::all()->random(1)->first();
        return $chapter;
    }

    private function createAnswer(User $user, Chapter $chapter): ChapterAnswer
    {
        $this->actingAs($user);

        $payload = [
            'chapter_id' => $chapter->id,
            'user_id' => $user->id,
            'answer' => 'test'
        ];

        $answer = $this->postJson(route('chapter-answers.store'), $payload)
            ->assertStatus(201);
        $this->assertDatabaseHas('chapter_answers', $payload);

        return ChapterAnswer::find($answer['data']['id']);
    }
}
