<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccentAudio;
class AccentAudioController extends TalkController
{
    function __construct(){
      $this->model = new AccentAudio();
    }
}
