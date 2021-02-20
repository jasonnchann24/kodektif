<?php

namespace App\Providers;

use App\Events\ArticleLikedEvent;
use App\Events\ArticleUnlikedEvent;
use App\Listeners\ArticleLikedListener;
use App\Listeners\ArticleUnlikedListener;
use App\Models\Article;
use App\Models\Post;
use App\Observers\ArticleObserver;
use App\Observers\PostObeserver;
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

        ArticleUnlikedEvent::class => [
            ArticleUnlikedListener::class
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
        Post::observe(PostObeserver::class);
    }
}
