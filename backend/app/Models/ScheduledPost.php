<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'post_text',
        'media_url',
        'media_type',
        'scheduled_datetime',
        'timezone',
        'status',
    ];
}
