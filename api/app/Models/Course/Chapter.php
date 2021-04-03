<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
