<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnabledLesson extends APIModel
{
    protected $table = 'enabled_lessons';
    protected $fillable = ['account_id', 'lesson_id'];
}
