<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logger;
use Carbon\Carbon;
class LoggerController extends TalkController
{
    function __construct(){
    	$this->model = new Logger();
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $date = $data['date'];
      $results = null;
      if($date == null){
        $this->model = new Logger();
        $this->retrieveDB(array());
        return $this->response();
      }else{
        $results = Logger::whereDate('created_at', '=', $date)->get();
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
