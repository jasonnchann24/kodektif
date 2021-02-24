<?php

namespace App\Models\Post\PostComment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCommentReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_comment_id',
        'body',
        'mentions'
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
