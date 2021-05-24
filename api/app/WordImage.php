<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordImage extends APIModel
{
    protected $table = 'word_images';
    protected $fillable = ['word_audio_id', 'type', 'url'];
}
