<?php

namespace Tests\Feature\Http\Controllers\Article;

use App\Models\Article;
use App\Models\ArticleLike;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class ArticleLikeControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(UserProfileSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(LanguageSeeder::class);
        $this->seed(ArticleSeeder::class);
    }

    /** @test */
    public function non_authenticated_user_can_not_access_these_apis()
    {
        $this->json('POST', "/api/article-likes", ['article_id' => -1])
            ->assertStatus(401);

        $this->json('DELETE', "/api/article-likes/-1")
            ->assertStatus(401);
    }

    /** @test */
    public function user_can_like_article()
    {
        $user = $this->createBasicUser();
        $article = $this->getOneArticle();

        $this->likeArticle($user, $article);

        $this->assertDatabaseHas(
            'article_likes',
            [
                'user_id' => $user->id,
                'article_id' => $article->id
            ]
        );
    }

    /** @test */
    public function user_can_unlike_article()
    {
        $user = $this->createBasicUser();
        $article = $this->getOneArticle();

        $like = $this->likeArticle($user, $article);

        $this->json('DELETE', "/api/article-likes/{$like->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing(
            'article_likes',
            [
                'user_id' => $user->id,
                'article_id' => $article->id
            ]
        );
    }

    /** @test */
    public function user_can_not_double_like_article()
    {
        $user = $this->createBasicUser();
        $article = $this->getOneArticle();

        $this->likeArticle($user, $article);
        $this->likeArticle($user, $article);

        $like = ArticleLike::where('user_id', $user->id)->where('article_id', $article->id)->get();
        $this->assertEquals(1, $like->count());
    }

    protected function getOneArticle(): Article
    {
        $article = Article::all()->random(1)->first();
        return $article;
    }

    protected function likeArticle($user, $article): ArticleLike
    {
        $this->actingAs($user);

        $res = $this->json('POST', '/api/article-likes', ['article_id' => $article->id])
            ->assertStatus(201);

        $this->assertDatabaseHas('article_likes', [
            'user_id' => $user->id,
            'article_id' => $article->id
        ]);

        $like = ArticleLike::find($res['id']);
        return $like;
    }
}
