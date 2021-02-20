<?php

namespace Database\Seeders\Article;

use App\Models\ArticleLike;
use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $articles = Article::all();

        for ($i = 0; $i < 100; $i++) {
            do {
                $randomUser = $users->random(1)->first();
                $randomArticle = $articles->random(1)->first();
                $likesCount = ArticleLike::where('user_id', $randomUser->id)
                    ->where('article_id', $randomArticle->id)
                    ->count();
            } while ($likesCount > 0);

            $article = $articles->find($randomArticle->id);
            $article->likes_count = $article->likes_count + 1;
            $article->save();

            ArticleLike::factory([
                'user_id' => $randomUser->id,
                'article_id' => $randomArticle->id
            ])->create();
        }
    }
}
