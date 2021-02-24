<?php

namespace App\Models\Post\PostComment;

use App\Http\Resources\Post\PostComment\PostCommentVoteResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
        'mentions',
        'upvote_count',
        'downvote_count'
    ];

    protected $with = [
        'user', 'postCommentVotes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function postCommentVotes()
    {
        return $this->hasMany(PostCommentVote::class);
    }

    public function postCommentReplies()
    {
        return $this->hasMany(PostCommentReply::class);
    }

    public function getHasVotedAttribute()
    {
        $postCommentVote = $this->postCommentVotes()
            ->where('user_id', Auth::id())
            ->first();

        return $postCommentVote
            ? new PostCommentVoteResource($postCommentVote)
            : null;
    }
}
