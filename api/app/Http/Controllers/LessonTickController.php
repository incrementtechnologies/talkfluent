<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LessonTick;
class LessonTickController extends TalkController
{
    function __construct(){
      $this->model = new LessonTick();
    }

    public function update(Request $request){
      $data = $request->all();
      $data['flag'] = ($data['flag'] == "true") ? 1 : 0;
      $accountId = $data['account_id'];
      $lessonId = $data['lesson_id'];
      $result = LessonTick::where('account_id', '=', $accountId)->where('lesson_id', '=', $lessonId)->get();

      if(sizeof($result) > 0){
        // Update
        $data['id'] = $result[0]['id'];
        $this->model = new LessonTick();
        $this->updateDB($data);
        return $this->response();
      }else{
        // Insert
        $this->model = new LessonTick();
        $this->insertDB($data);
        return $this->response();
      }
    }
}
