<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedBack extends APIModel
{
    protected $table = 'feedbacks';
    protected $fillable = ['account_id', 'message'];
}
