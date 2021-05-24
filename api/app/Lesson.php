<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends APIModel
{
    protected $table = 'lessons';
    protected $fillable = ['category_lesson_id', 'title','lesson_audio', 'english_audio', 'grammar_tip', 'grammar_audio', 'culture_tip', 'culture_audio', 'status', 'published_at', 'grammary_test_content_audio', 'grammar_question'];

    public function content(){
      return $this->hasMany('App\Content');
    }
}
