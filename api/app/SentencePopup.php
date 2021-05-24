<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SentencePopup extends APIModel
{
    protected $table = 'sentence_popups';
    protected $fillable = ['id', 'content_id', 'original', 'translation', 'audio',  'path', 'syllabus', 'syllabus_audio'];

    public function content(){
      return $this->hasOne('App\Content', 'content_id');
    }
}
