<?php

namespace App\Http\Controllers\Article;

use App\Events\ArticleLikedEvent;
use App\Events\ArticleUnlikedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleLikeStoreRequest;
use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ArticleLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Article\ArticleLikeStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleLikeStoreRequest $request)
    {
        $validated = $request->validated();

        $like = ArticleLike::firstOrCreate($validated);

        if ($like->wasRecentlyCreated) {
            $article = Article::find($validated['article_id']);
            ArticleLikedEvent::dispatch($article);
        }

        return Response::json($like, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ArticleLike  $articleLike
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArticleLike $articleLike)
    {
        $articleId = $articleLike->article_id;
        $article = Article::find($articleId);
        ArticleUnlikedEvent::dispatch($article);

        $articleLike->delete();

        return Response::json('', 204);
    }
}
