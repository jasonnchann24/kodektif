<?php

namespace App\Models\Course;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'slug',
        'chapter_count'
    ];

    protected $with = ['user', 'categories', 'chapters'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function doneUsers()
    {
        return $this->belongsToMany(User::class);
    }

    public function getHasDoneAttribute()
    {
        $done =  $this->doneUsers()->where('user_id', Auth::id())->first();
        return $done ? true : false;
    }
}
