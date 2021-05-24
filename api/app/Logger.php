<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logger extends APIModel
{
    protected $table = 'loggers';
    protected $fillable = ['account_id', 'payload', 'message'];
}
