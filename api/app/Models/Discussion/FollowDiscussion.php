<?php

namespace App\Models\Discussion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowDiscussion extends Model
{
    use HasFactory;

    protected $table = 'follow_discussions';

    protected $fillable = [
        'user_id',
        'discussion_id'
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
