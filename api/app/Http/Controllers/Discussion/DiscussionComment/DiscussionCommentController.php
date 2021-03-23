<?php

namespace App\Http\Controllers\Discussion\DiscussionComment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discussion\DiscussionComment\DiscussionCommentStoreRequest;
use App\Http\Resources\Discussion\DiscussionComment\DiscussionCommentResource;
use App\Models\Discussion\DiscussionComment\DiscussionComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiscussionCommentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'not.suspended']);
        $this->middleware('throttle:tight-throttle')->except('show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Discussion\DiscussionComment\DiscussionCommentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscussionCommentStoreRequest $request)
    {
        $validated = $request->validated();

        $discussionComment = DiscussionComment::create($validated);

        return Response::json($discussionComment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionComment  $discussionComment
     * @return \Illuminate\Http\Response
     */
    public function show(DiscussionComment $discussionComment)
    {
        return new DiscussionCommentResource($discussionComment);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionComment  $discussionComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscussionComment $discussionComment)
    {
        $this->authorize('delete', $discussionComment);
        $discussionComment->delete();
        return Response::json('', 204);
    }
}
