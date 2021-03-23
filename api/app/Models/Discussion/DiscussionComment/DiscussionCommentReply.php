<?php

namespace App\Models\Discussion\DiscussionComment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscussionCommentReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'discussion_comment_id',
        'body',
        'mentions'
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
