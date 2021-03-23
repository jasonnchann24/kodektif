<?php

namespace App\Models\Discussion;

use App\Http\Resources\Discussion\DiscussionVoteResource;
use App\Models\Category;
use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    protected $with = [
        'user', 'language', 'categories', 'discussionVotes'
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

    public function discussionComments()
    {
        return $this->hasMany(DiscussionComment::class);
    }

    public function getHasVotedAttribute()
    {
        $discussionVote = $this->discussionVotes()
            ->where('user_id', Auth::id())
            ->first();

        return $discussionVote ? new DiscussionVoteResource($discussionVote) : null;
    }
}
