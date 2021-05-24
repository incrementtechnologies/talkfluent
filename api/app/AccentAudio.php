<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccentAudio extends APIModel
{
    protected $table = 'accent_audios';
    protected $fillable = ['lesson_id', 'title', 'url'];
}
