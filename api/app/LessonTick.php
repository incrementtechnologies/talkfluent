<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonTick extends APIModel
{
    protected $table = 'lesson_ticks';
    protected $fillable = ['lesson_id', 'account_id',  'flag'];
}
