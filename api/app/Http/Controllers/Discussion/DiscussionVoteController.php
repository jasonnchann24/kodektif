<?php

namespace App\Http\Controllers\Discussion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discussion\DiscussionVote\DiscussionVoteStoreRequest;
use App\Http\Requests\Discussion\DiscussionVote\DiscussionVoteUpdateRequest;
use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiscussionVoteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('not.suspended');
        $this->middleware('throttle:tight-throttle');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Discussion\DiscussionVote\DiscussionVoteStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscussionVoteStoreRequest $request)
    {
        $validated = $request->validated();
        $discussionVote = DiscussionVote::firstOrCreate(
            [
                'user_id' => $validated['user_id'],
                'discussion_id' => $validated['discussion_id']
            ],
            [
                'upvote' => $validated['upvote']
            ]
        );

        return Response::json($discussionVote, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Discussion\DiscussionVote\DiscussionVoteUpdateRequest  $request
     * @param  \App\Models\DiscussionVote  $discussionVote
     * @return \Illuminate\Http\Response
     */
    public function update(DiscussionVoteUpdateRequest $request, DiscussionVote $discussionVote)
    {
        $validated = $request->validated();
        $this->authorize('update', $discussionVote);

        $discussionVote->update($validated);
        $discussionVote->save();

        return Response::json($discussionVote, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DiscussionVote  $discussionVote
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscussionVote $discussionVote)
    {
        $this->authorize('delete', $discussionVote);

        $discussionVote->delete();

        return Response::json('', 204);
    }
}
