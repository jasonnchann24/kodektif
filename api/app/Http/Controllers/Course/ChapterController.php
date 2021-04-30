<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\ChapterStoreRequest;
use App\Http\Requests\Course\ChapterUpdateRequest;
use App\Http\Resources\Course\ChapterResource;
use App\Models\Course\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('role.check:admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Course\ChapterStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChapterStoreRequest $request)
    {
        $validated = $request->validated();

        $chapter = Chapter::create($validated);

        return (new ChapterResource($chapter))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Course\ChapterU[dateRequest  $request
     * @param  \App\Models\Course\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(ChapterUpdateRequest $request, Chapter $chapter)
    {
        $validated = $request->validated();

        $chapter->update($validated);
        $chapter->save();

        return (new ChapterResource($chapter))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return Response::json('', 204);
    }
}
