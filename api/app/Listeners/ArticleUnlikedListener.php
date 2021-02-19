<?php

namespace App\Listeners;

use App\Events\ArticleUnlikedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ArticleUnlikedListener implements ShouldQueue
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
     * @param  ArticleUnlikedEvent  $event
     * @return void
     */
    public function handle(ArticleUnlikedEvent $event)
    {
        $article = $event->article;

        $article->likes_count = $article->likes_count - 1;
        $article->save();
    }
}
