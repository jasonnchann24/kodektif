<?php

namespace App\Models\Post\PostComment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCommentVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_comment_id',
        'upvote'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postComment()
    {
        return $this->belongsTo(PostComment::class);
    }
}
