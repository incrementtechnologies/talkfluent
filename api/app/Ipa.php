<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ipa extends APIModel
{
    protected $table = 'ipas';
    protected $fillable = ['title', 'subtitle', 'code', 'bottom', 'text', 'category', 'audio', 'video'];
}
