<?php

use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Article\ArticleLikeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\Post\MyPostController;
use App\Http\Controllers\Post\PostComment\PostCommentController;
use App\Http\Controllers\Post\PostComment\PostCommentReplyController;
use App\Http\Controllers\Post\PostComment\PostCommentVoteController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostVoteController;
use App\Http\Controllers\SuspendController;
use App\Http\Controllers\UserProfileController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', MeController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::post('suspend-user', [SuspendController::class, 'store'])->name('suspend-user.store');
    Route::delete('unsuspend-user/{id}', [SuspendController::class, 'destroy'])->name('suspend-user.destroy');
    Route::apiResource('article-likes', ArticleLikeController::class)->except(['index', 'update', 'show']);
});

Route::apiResource('user-profiles', UserProfileController::class)->except(['index', 'destroy']);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('languages', LanguageController::class);

Route::get('/posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::apiResource('posts', PostController::class)->except('show');
Route::apiResource('post-votes', PostVoteController::class)->except(['index', 'show']);
Route::get('/my-posts', MyPostController::class)->name('my-posts');
Route::apiResource('post-comments', PostCommentController::class)->except(['update', 'index']);
Route::apiResource('post-comment-votes', PostCommentVoteController::class)->except(['index', 'show']);
Route::apiResource('post-comment-replies', PostCommentReplyController::class)->except(['index', 'update', 'show']);

Route::get('/articles/{article}/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::apiResource('articles', ArticleController::class)->except(['show']);
