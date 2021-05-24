<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpaClassification extends APIModel
{
  
  protected $table = 'ipa_classifications';
  protected $fillable = ['title', 'text'];
}
