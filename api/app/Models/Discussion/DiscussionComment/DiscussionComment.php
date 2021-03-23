<?php

namespace App\Models\Discussion\DiscussionComment;

use App\Http\Resources\Discussion\DiscussionComment\DiscussionCommentVoteResource;
use App\Models\Discussion\Discussion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DiscussionComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'discussion_id',
        'body',
        'mentions',
        'upvote_count',
        'downvote_count'
    ];

    protected $with = [
        'user', 'discussionCommentVotes', 'discussionCommentReplies'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function discussionCommentVotes()
    {
        return $this->hasMany(DiscussionCommentVote::class);
    }

    public function discussionCommentReplies()
    {
        return $this->hasMany(DiscussionCommentReply::class);
    }

    public function getHasVotedAttribute()
    {
        $postCommentVote = $this->discussionCommentVotes()
            ->where('user_id', Auth::id())
            ->first();

        return $postCommentVote
            ? new DiscussionCommentVoteResource($postCommentVote)
            : null;
    }
}
