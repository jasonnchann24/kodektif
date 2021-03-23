<?php

namespace App\Models;

use App\Models\Discussion\Discussion;
use App\Models\Post\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'iso_639_1',
        'slug'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }
}
