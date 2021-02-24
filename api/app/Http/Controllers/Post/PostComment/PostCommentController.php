<?php

namespace App\Http\Controllers\Post\PostComment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostComment\PostCommentStoreRequest;
use App\Http\Resources\Post\PostComment\PostCommentResource;
use App\Models\Post\PostComment\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'not.suspended']);
        $this->middleware('throttle:tight-throttle')->except('show');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\PostComment\PostCommentStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCommentStoreRequest $request)
    {
        $validated = $request->validated();

        $postComment = PostComment::create($validated);

        return Response::json($postComment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post\PostComment\PostComment  $postComment
     * @return \Illuminate\Http\Response
     */
    public function show(PostComment $postComment)
    {
        return new PostCommentResource($postComment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post\PostComment\PostComment  $postComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostComment $postComment)
    {
        $this->authorize('delete', $postComment);
        $postComment->delete();
        return Response::json('', 204);
    }
}
