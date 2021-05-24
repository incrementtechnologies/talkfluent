<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test;
class TestController extends TalkController
{
  function __construct(){
    $this->model = new Test();
  }

  public function update(Request $request){
    $data = $request->all();
    $condition = array(
      array('lesson_id', '=', $data['lesson_id']),
      array('account_id', '=', $data['account_id'])
    );
    $result = Test::where($condition)->get();

    if(sizeof($result) > 0){
      $this->model = new Test();
      $data['id'] = $result[0]['id'];
      $this->updateDB($data);
    }else{
      $this->model = new Test();
      $this->insertDB($data);
    }
    return $this->response();
  }
}
