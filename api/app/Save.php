<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Save extends APIModel
{
    protected $table = 'words_saves';
    protected $fillable = ['word_audio_id', 'account_id'];
}
