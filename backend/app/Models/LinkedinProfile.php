<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkedinProfile extends Model
{
    use HasFactory;

    protected $table = 'linkedin_profiles';

    protected $fillable = [
        'user_id',
        'person_id',
        'name',                // <--- add this
        'headline',
        'profile_picture_url',
        'access_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
