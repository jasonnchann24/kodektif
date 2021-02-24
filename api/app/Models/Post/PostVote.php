<?php

namespace App\Models\Post;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'upvote'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
