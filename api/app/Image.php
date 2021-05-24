<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends APIModel
{
    protected $table = 'images';
    protected $fillable = ['url'];
}
