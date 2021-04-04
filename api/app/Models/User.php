<?php

namespace App\Models;

use App\Models\Course\ChapterAnswer;
use App\Models\Course\Course;
use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\DiscussionComment\DiscussionCommentReply;
use App\Models\Discussion\DiscussionVote;
use App\Models\Discussion\FollowDiscussion;
use App\Models\Post\Post;
use App\Models\Post\PostComment\PostComment;
use App\Models\Post\PostComment\PostCommentReply;
use App\Models\Post\PostVote;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'roles', 'provider'
    ];

    public function provider()
    {
        return $this->hasOne(Provider::class, 'user_id', 'id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function articleLikes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function postVotes()
    {
        return $this->hasMany(PostVote::class);
    }

    public function postComments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function postCommentReplies()
    {
        return $this->hasMany(PostCommentReply::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function discussionVotes()
    {
        return $this->hasMany(DiscussionVote::class);
    }

    public function discussionComments()
    {
        return $this->hasMany(DiscussionComment::class);
    }

    public function discussionCommentReplies()
    {
        return $this->hasMany(DiscussionCommentReply::class);
    }

    public function followDiscussions()
    {
        return $this->hasMany(FollowDiscussion::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function doneCourses()
    {
        return $this->belongsTo(Course::class);
    }

    public function chapterAnswers()
    {
        return $this->hasMany(ChapterAnswer::class);
    }


    public function is($roleName)
    {
        foreach ($this->roles()->get() as $userRole) {
            if ($userRole->name == $roleName) {
                return true;
            }

            return false;
        }
    }
}
