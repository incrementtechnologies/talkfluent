<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaveContent extends APIModel
{
    protected $table = 'save_contents';
    protected $fillable = ['content_id', 'account_id'];
}
