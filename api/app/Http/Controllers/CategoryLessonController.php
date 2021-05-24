<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryLesson;
class CategoryLessonController extends TalkController
{
     function __construct(){
      $this->model = new CategoryLesson();
    }
}
