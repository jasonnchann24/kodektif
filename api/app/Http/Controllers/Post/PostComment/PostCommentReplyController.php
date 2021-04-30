<?php

namespace App\Http\Controllers\Post\PostComment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostCommentReply\PostCommentReplyStoreRequest;
use App\Http\Resources\Post\PostComment\PostCommentReplyResource;
use App\Models\Post\PostComment\PostCommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PostCommentReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                'auth:sanctum',
                'not.suspended',
                'throttle:tight-throttle'
            ]
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\PostCommentReply\PostCommentReplyStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCommentReplyStoreRequest $request)
    {
        $validated = $request->validated();
        $reply = PostCommentReply::create($validated);

        return (new PostCommentReplyResource($reply))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post\PostComment\PostCommentReply  $postCommentReply
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostCommentReply $postCommentReply)
    {
        $this->authorize('delete', $postCommentReply);
        $postCommentReply->delete();

        return Response::json('', 204);
    }
}
