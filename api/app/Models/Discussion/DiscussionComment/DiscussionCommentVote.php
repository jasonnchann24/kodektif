<?php

namespace App\Models\Discussion\DiscussionComment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionCommentVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'discussion_comment_id',
        'upvote'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discussionComment()
    {
        return $this->belongsTo(DiscussionComment::class);
    }
}
