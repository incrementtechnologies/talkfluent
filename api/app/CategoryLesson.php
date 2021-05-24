<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryLesson extends APIModel
{
    protected $table = 'category_lessons';
    protected $fillable = ['top_lesson_id', 'title'];
}
