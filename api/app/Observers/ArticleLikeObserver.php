<?php

namespace App\Observers;

use App\Events\ArticleLikedEvent;
use App\Models\ArticleLike;
use App\Models\Article;

class ArticleLikeObserver
{
    /**
     * Handle the ArticleLike "created" event.
     *
     * @param  \App\Models\ArticleLike  $articleLike
     * @return void
     */
    public function created(ArticleLike $articleLike)
    {
        $article = Article::find($articleLike->article_id);
        ArticleLikedEvent::dispatch($article);
    }

    /**
     * Handle the ArticleLike "updated" event.
     *
     * @param  \App\Models\ArticleLike  $articleLike
     * @return void
     */
    public function updated(ArticleLike $articleLike)
    {
        //
    }

    /**
     * Handle the ArticleLike "deleted" event.
     *
     * @param  \App\Models\ArticleLike  $articleLike
     * @return void
     */
    public function deleted(ArticleLike $articleLike)
    {
        $article = Article::find($articleLike->article_id);
        $article->likes_count -= 1;
        $article->save();
    }

    /**
     * Handle the ArticleLike "restored" event.
     *
     * @param  \App\Models\ArticleLike  $articleLike
     * @return void
     */
    public function restored(ArticleLike $articleLike)
    {
        //
    }

    /**
     * Handle the ArticleLike "force deleted" event.
     *
     * @param  \App\Models\ArticleLike  $articleLike
     * @return void
     */
    public function forceDeleted(ArticleLike $articleLike)
    {
        //
    }
}
