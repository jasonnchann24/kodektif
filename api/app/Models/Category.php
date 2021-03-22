<?php

namespace App\Models;

use App\Models\Discussion\Discussion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function allSubCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('subCategories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function discussions()
    {
        return $this->belongsToMany(Discussion::class);
    }
}
