<?php

namespace App\Providers;

use App\Events\ArticleLikedEvent;
use App\Events\ArticleUnlikedEvent;
use App\Events\DiscussionCommentVotedEvent;
use App\Events\DiscussionVotedEvent;
use App\Events\PostCommentVotedEvent;
use App\Events\PostVotedEvent;
use App\Listeners\ArticleLikedListener;
use App\Listeners\ArticleUnlikedListener;
use App\Listeners\DiscussionCommentVotedListener;
use App\Listeners\DiscussionVotedListener;
use App\Listeners\PostCommentVotedListener;
use App\Listeners\PostVotedListener;
use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionComment\DiscussionCommentVote;
use App\Models\Discussion\DiscussionVote;
use App\Models\Post\Post;
use App\Models\Post\PostComment\PostCommentVote;
use App\Models\Post\PostVote;
use App\Observers\ArticleLikeObserver;
use App\Observers\ArticleObserver;
use App\Observers\DiscussionCommentVoteObserver;
use App\Observers\DiscussionObserver;
use App\Observers\DiscussionVoteObserver;
use App\Observers\PostCommentVoteObserver;
use App\Observers\PostObeserver;
use App\Observers\PostVoteObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ArticleLikedEvent::class => [
            ArticleLikedListener::class
        ],

        PostVotedEvent::class => [
            PostVotedListener::class
        ],

        PostCommentVotedEvent::class => [
            PostCommentVotedListener::class
        ],

        DiscussionVotedEvent::class => [
            DiscussionVotedListener::class
        ],

        DiscussionCommentVotedEvent::class => [
            DiscussionCommentVotedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Article::observe(ArticleObserver::class);
        ArticleLike::observe(ArticleLikeObserver::class);
        Post::observe(PostObeserver::class);
        PostVote::observe(PostVoteObserver::class);
        PostCommentVote::observe(PostCommentVoteObserver::class);
        Discussion::observe(DiscussionObserver::class);
        DiscussionVote::observe(DiscussionVoteObserver::class);
        DiscussionCommentVote::observe(DiscussionCommentVoteObserver::class);
    }
}
