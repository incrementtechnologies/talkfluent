<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioFile extends APIModel
{
    protected $table = 'audio_files';
    protected $fillable = ['filename', 'url'];
}
