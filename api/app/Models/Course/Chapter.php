<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'order',
        'title',
        'slug'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function chapterAnswers()
    {
        return $this->hasMany(ChapterAnswer::class);
    }

    public function getUserChapterDoneAttribute()
    {
        return $this->chapterAnswers()->where('user_id', Auth::id())->first();
    }
}
