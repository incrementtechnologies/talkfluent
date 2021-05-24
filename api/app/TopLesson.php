<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopLesson extends APIModel
{
    protected $table = 'top_lessons';
    protected $fillable = ['title'];
}
