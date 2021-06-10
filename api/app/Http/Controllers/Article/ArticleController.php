<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class ArticleController extends Controller
{
    protected $user;

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
        return Cache::tags('articles-index')
            ->remember('articles-' . request()->get('page', 1), 33600, function () {
                return ArticleResource::collection(
                    Article::latest()->paginate(6)
                );
            });
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

            $newArticle['user_id'] = Auth::id();

            $article = Article::create($newArticle);

            $article->categories()
                ->sync($newCategories);

            if ($request->hasFile('image')) {
                $articleImage = $this->saveImage($request);
                $article->articleImage()->associate($articleImage);
                $article->save();
            }

            Cache::tags('articles-index')->flush();

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

            if ($request->hasFile('image')) {
                $articleImage = $this->saveImage($request);
                $article->articleImage()->associate($articleImage);
            }

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

    private function saveImage(Request $request)
    {
        $image = $request->file('image');

        $width = 600; // max width
        $height = 600; // max height
        $img = Image::make($image);
        $img->height() > $img->width() ? $width = null : $height = null;
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
        $img->save(storage_path("app/public/article_images/" . $filename), 100);

        $target = 'article_images/' . $filename;
        $articleImage = ArticleImage::create([
            'filename' => $target
        ]);

        return $articleImage;
    }
}
