<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends APIModel
{
    protected $table = 'contents';
    protected $fillable = ['original', 'translation', 'audio', 'path', 'syllabus', 'syllabus_audio', 'text_accent'];

    public function lesson(){
      return $this->belongsTo('App\Lesson', 'lesson_id');
    }

    public function answer(){
      return $this->hasMany('App\Answer');
    }
}
