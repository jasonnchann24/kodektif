<?php

namespace App\Models\Post;

use App\Http\Resources\Post\PostVoteResource;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post\PostComment\PostComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'language_id',
        'title',
        'description',
        'body',
        'slug',
        'upvote_count',
        'downvote_count'
    ];

    protected $with = [
        'user', 'language', 'categories', 'postVotes', 'postComments'
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

    public function postVotes()
    {
        return $this->hasMany(PostVote::class);
    }

    public function postComments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function getHasVotedAttribute()
    {
        $postVote = $this->postVotes()
            ->where('user_id', Auth::id())
            ->first();

        return $postVote ? new PostVoteResource($postVote) : null;
    }
}
