<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role.check:admin')->only(['store', 'update', 'destroy']);
        $this->user = authUser();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  ArticleResource::collection(
            Article::paginate(6)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Article\ArticleStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleStoreRequest $request)
    {
        $validated = $request->validated();
        $newArticle = Arr::except($validated, ['categories']);
        $newCategories = $validated['categories'];

        try {
            DB::beginTransaction();

            $article = $this->user->articles()
                ->create($newArticle);

            $article->categories()
                ->sync($newCategories);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return Response::json($article, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Article\ArticleUpdateRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        $validated = $request->validated();
        $this->authorize('update', $article);

        try {
            DB::beginTransaction();

            $article->update(Arr::except($validated, ['categories']));

            $article->categories()
                ->sync($validated['categories']);

            $article->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();
        return Response::json('', 204);
    }
}
