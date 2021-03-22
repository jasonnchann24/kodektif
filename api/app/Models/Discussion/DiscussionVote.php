<?php

namespace App\Models\Discussion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'discussion_id',
        'upvote'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }
}
