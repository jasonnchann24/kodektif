<?php

namespace App\Http\Controllers\Discussion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discussion\DiscussionStoreRequest;
use App\Http\Requests\Discussion\DiscussionUpdateRequest;
use App\Http\Resources\Discussion\DiscussionResource;
use App\Models\Discussion\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class DiscussionController extends Controller
{

    public function __construct()
    {
        $this->middleware(
            [
                'auth:sanctum',
                'not.suspended'
            ]
        )->except(['index', 'show']);

        $this->user = authUser();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DiscussionResource::collection(Discussion::latest()->paginate(30));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Discussion\DiscussionStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscussionStoreRequest $request)
    {
        $validated = $request->validated();
        $newDiscussion = Arr::except($validated, ['categories']);
        $newCategories = $validated['categories'];

        try {
            DB::beginTransaction();

            $discussion = $this->user->discussions()->create($newDiscussion);
            $discussion->categories()->sync($newCategories);

            DB::commit();
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return (new DiscussionResource($discussion))
            ->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return \Illuminate\Http\Response
     */
    public function show(Discussion $discussion)
    {
        return new DiscussionResource($discussion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Discussion\DiscussionUpdateRequest $request
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return \Illuminate\Http\Response
     */
    public function update(DiscussionUpdateRequest $request, Discussion $discussion)
    {
        $validated = $request->validated();
        $this->authorize('update', $discussion);

        $updatedDiscussion = Arr::except($validated, ['categories']);
        $newCategories = $validated['categories'];

        try {
            DB::beginTransaction();

            $discussion->update($updatedDiscussion);
            $discussion->categories()->sync($newCategories);

            $discussion->save();

            DB::commit();
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return (new DiscussionResource($discussion))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discussion $discussion)
    {
        $this->authorize('delete', $discussion);

        $discussion->delete();
        return Response::json('', 204);
    }
}
