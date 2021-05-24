<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IpaClassification;
class IpaClassficationController extends TalkController
{
    function __construct(){
      $this->model = new IpaClassification();
    }
}
