<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TopLesson;
class TopLessonController extends TalkController
{
    function __construct(){
      $this->model = new TopLesson();
    }
}
