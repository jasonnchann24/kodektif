<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'article_image_id',
        'language_id',
        'title',
        'description',
        'body',
        'slug'
    ];

    protected $with = [
        'user', 'categories', 'language', 'likes', 'articleImage'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function likes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    public function articleImage()
    {
        return $this->belongsTo(ArticleImage::class);
    }

    public function getHasLikedAttribute()
    {
        $articleLike = $this->likes()
            ->where('user_id', Auth::id())
            ->first();

        return $articleLike ? $articleLike : false;
    }
}
