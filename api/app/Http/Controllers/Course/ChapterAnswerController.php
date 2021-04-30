<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\ChapterAnswerResource;
use App\Models\Course\Chapter;
use App\Models\Course\ChapterAnswer;
use App\Models\Course\Course;
use App\Models\Course\CourseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['not.suspended']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'chapter_id' => 'required|exists:chapters,id',
            'answer' => 'required|string'
        ]);

        $validated['user_id'] = Auth::id();

        $answer = ChapterAnswer::firstOrCreate(
            [
                'chapter_id' => $validated['chapter_id'],
                'user_id' => Auth::id()
            ],
            [
                'answer' => $validated['answer']
            ]
        );

        $chapter = Chapter::findOrFail($request->input('chapter_id'));
        $course = $chapter->course()->first();
        if ($this->checkIfUserHasCompletedAllChapters($course)) {
            CourseUser::firstOrCreate([
                'course_id'  => $course['id'],
                'user_id' => Auth::id()
            ]);
        }

        return (new ChapterAnswerResource($answer))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course\ChapterAnswer  $chapterAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(ChapterAnswer $chapterAnswer)
    {
        return new ChapterAnswerResource($chapterAnswer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course\ChapterAnswer  $chapterAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChapterAnswer $chapterAnswer)
    {
        $this->authorize('update', $chapterAnswer);

        $validated = $this->validate($request, [
            'answer' => 'required|string'
        ]);

        $chapterAnswer->update([
            'answer' => $validated['answer']
        ]);

        $chapterAnswer->save();

        return (new ChapterAnswerResource($chapterAnswer))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course\ChapterAnswer  $chapterAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChapterAnswer $chapterAnswer)
    {
        //
    }

    private function checkIfUserHasCompletedAllChapters(Course $course): bool
    {
        $done = true;

        $chapters = $course->chapters()->get();

        foreach ($chapters as $chapter) {
            $userChapterDone = $chapter->userChapterDone;
            if (!$userChapterDone) {
                $done = false;
                break;
            }
        }

        return $done;
    }
}
