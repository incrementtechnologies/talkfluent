<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordPopup extends APIModel
{
    protected $table = 'word_popups';
    protected $fillable = ['original', 'audio', 'translation', 'content_id'];
}
