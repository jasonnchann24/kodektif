<?php

namespace App\Models;

use App\Models\Post\PostComment\PostComment;
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
