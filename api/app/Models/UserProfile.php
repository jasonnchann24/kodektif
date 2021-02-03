<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'country',
        'about',
        'facebook_link',
        'linkedin_link',
        'github_link',
        'others_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
