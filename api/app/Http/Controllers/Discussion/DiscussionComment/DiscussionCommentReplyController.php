<?php

namespace App\Http\Controllers\Discussion\DiscussionComment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discussion\DiscussionCommentReply\DiscussionCommentReplyStoreRequest;
use App\Models\Discussion\DiscussionComment\DiscussionCommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiscussionCommentReplyController extends Controller
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
     * @param  \App\Http\Requests\Discussion\DiscussionCommentReply\DiscussionCommentReplyStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscussionCommentReplyStoreRequest $request)
    {
        $validated = $request->validated();
        $reply = DiscussionCommentReply::create($validated);

        return Response::json($reply, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentReply  $discussionCommentReply
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscussionCommentReply $discussionCommentReply)
    {
        $this->authorize('delete', $discussionCommentReply);
        $discussionCommentReply->delete();

        return Response::json('', 204);
    }
}
