<?php

namespace App\Http\Controllers\Post\PostComment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostCommentVote\PostCommentVoteStoreRequest;
use App\Http\Requests\Post\PostCommentVote\PostCommentVoteUpdateRequest;
use App\Models\Post\PostComment\PostCommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PostCommentVoteController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth:sanctum', 'throttle:tight-throttle', 'not.suspended'
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\PostCommentVote\PostCommentVoteStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCommentVoteStoreRequest $request)
    {
        $validated = $request->validated();
        $commentVote = PostCommentVote::firstOrCreate(
            [
                'user_id' => $validated['user_id'],
                'post_comment_id' => $validated['post_comment_id']
            ],
            [
                'upvote' => $validated['upvote']
            ]
        );

        return Response::json($commentVote, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Post\PostCommentVote\PostCommentVoteUpdateRequest  $request
     * @param  \App\Models\Post\PostComment\PostCommentVote  $postCommentVote
     * @return \Illuminate\Http\Response
     */
    public function update(PostCommentVoteUpdateRequest $request, PostCommentVote $postCommentVote)
    {
        $this->authorize('update', $postCommentVote);
        $validated = $request->validated();
        $postCommentVote->update($validated);
        $postCommentVote->save();

        return Response::json($postCommentVote, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post\PostComment\PostCommentVote  $postCommentVote
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostCommentVote $postCommentVote)
    {
        $this->authorize('delete', $postCommentVote);
        $postCommentVote->delete();

        return Response::json('', 204);
    }
}
