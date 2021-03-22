<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Discussion\Discussion;
use App\Models\Post;
use App\Models\Post\PostComment\PostComment;
use App\Models\Post\PostComment\PostCommentVote;
use App\Models\UserProfile;
use App\Policies\ArticlePolicy;
use App\Policies\DiscussionPolicy;
use App\Policies\PostCommentPolicy;
use App\Policies\PostCommentVotePolicy;
use App\Policies\PostPolicy;
use App\Policies\UserProfilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        UserProfile::class => UserProfilePolicy::class,
        Article::class => ArticlePolicy::class,
        Post::class => PostPolicy::class,
        PostComment::class => PostCommentPolicy::class,
        PostCommentVote::class => PostCommentVotePolicy::class,
        Discussion::class => DiscussionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
