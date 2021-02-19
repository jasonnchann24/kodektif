<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleLikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleLike::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::all();
        $articles = Article::all();

        do {
            $randomUser = $users->random(1)->first();
            $randomArticle = $articles->random(1)->first();
            $likes = ArticleLike::where('user_id', $randomUser->id)
                ->where('article_id', $randomArticle->id)
                ->get();
        } while ($likes->count() > 0);

        $article = Article::find($randomArticle->id);
        $article->likes_count = $article->likes_count + 1;
        $article->save();

        return [
            'user_id' => $randomUser->id,
            'article_id' => $randomArticle->id
        ];
    }
}
