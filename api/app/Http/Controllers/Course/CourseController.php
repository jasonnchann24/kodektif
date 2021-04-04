<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseStoreRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('role.check:admin')->only(['store', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CourseResource::collection(Course::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Course\CourseStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $course = Course::create(Arr::except($validated, 'categories'));

            $course->categories()
                ->sync($validated['categories']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return (new CourseResource($course))
            ->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Course\CourseUpdaterequest  $request
     * @param  \App\Models\Course\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, Course $course)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $course->update(Arr::except($validated, 'categories'));

            $course->categories()
                ->sync($validated['categories']);

            $course->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return (new CourseResource($course))
            ->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return Response::json('', 204);
    }
}
