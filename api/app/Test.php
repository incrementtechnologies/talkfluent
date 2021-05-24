<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends APIModel
{
    protected $table = "tests";
    protected $fillable = ['lesson_id', 'account_id', 'number_of_days'];
}
