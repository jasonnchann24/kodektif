<?php

namespace App\Models\Discussion;

use App\Models\Category;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'language_id',
        'title',
        'body',
        'slug',
        'upvote_count',
        'downvote_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function discussionVotes()
    {
        return $this->hasMany(DiscussionVote::class);
    }
}
