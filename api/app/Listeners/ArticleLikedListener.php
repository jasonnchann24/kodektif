<?php

namespace App\Listeners;

use App\Events\ArticleLikedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ArticleLikedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ArticleLikedEvent  $event
     * @return void
     */
    public function handle(ArticleLikedEvent $event)
    {
        $article = $event->article;

        $article->likes_count = $article->likes_count + 1;
        $article->save();
    }
}
