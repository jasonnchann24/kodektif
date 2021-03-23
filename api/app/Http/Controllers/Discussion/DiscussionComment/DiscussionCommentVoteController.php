<?php

namespace App\Http\Controllers\Discussion\DiscussionComment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discussion\DiscussionCommentVote\DiscussionCommentVoteStoreRequest;
use App\Http\Requests\Discussion\DiscussionCommentVote\DiscussionCommentVoteUpdateRequest;
use App\Models\Discussion\DiscussionComment\DiscussionCommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiscussionCommentVoteController extends Controller
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
     * @param  \App\Http\Requests\Discussion\DiscussionCommentVote\DiscussionCommentVoteStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscussionCommentVoteStoreRequest $request)
    {
        $validated = $request->validated();
        $commentVote = DiscussionCommentVote::firstOrCreate(
            [
                'user_id' => $validated['user_id'],
                'discussion_comment_id' => $validated['discussion_comment_id']
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
     * @param  \App\Http\Requests\Discussion\DiscussionCommentVote\DiscussionCommentVoteUpdateRequest  $request
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return \Illuminate\Http\Response
     */
    public function update(DiscussionCommentVoteUpdateRequest $request, DiscussionCommentVote $discussionCommentVote)
    {
        $this->authorize('update', $discussionCommentVote);
        $validated = $request->validated();
        $discussionCommentVote->update($validated);
        $discussionCommentVote->save();

        return Response::json($discussionCommentVote, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscussionCommentVote $discussionCommentVote)
    {
        $this->authorize('delete', $discussionCommentVote);
        $discussionCommentVote->delete();

        return Response::json('', 204);
    }
}
