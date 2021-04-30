<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename'
    ];

    public function article()
    {
        return $this->hasOne(Article::class);
    }
}
