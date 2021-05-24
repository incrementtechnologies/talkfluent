<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccentVideo;

class AccentVideoController extends TalkController
{
    function __construct(){
      $this->model = new AccentVideo();
    }
}
