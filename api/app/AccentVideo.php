<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccentVideo extends APIModel
{
    protected $table = 'accent_videos';
    protected $fillable = ['lesson_id', 'title', 'url'];
}
