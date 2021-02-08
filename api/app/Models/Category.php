<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id'
    ];

    public function subCategories()
    {
        return $this->hasMany(Category::class);
    }

    public function allSubCategories()
    {
        return $this->hasMany(Category::class)->with('subCategories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
