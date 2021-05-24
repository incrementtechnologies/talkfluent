<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FeedBack;
use Carbon\Carbon;
class FeedbackController extends TalkController
{
  function __construct(){
  	$this->model = new FeedBack();
  }

	public function retrieve(Request $request){
      $data = $request->all();
      $date = $data['date'];
      $results = null;
      if($date == null){
        $this->model = new FeedBack();
        $this->retrieveDB(array());
        return $this->response();
      }else{
        $results = FeedBack::whereDate('created_at', '=', $date)->get();
        return response()->json(
          array(
            'data'  => $results,
            'error' => null,
            'timestamps'  => Carbon::now()
          )
        );
      }
    }
}
