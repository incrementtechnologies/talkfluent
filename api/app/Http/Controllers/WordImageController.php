<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WordImage;
class WordImageController extends TalkController
{
    function __construct(){
      $this->model = new WordImage();
    }
}
