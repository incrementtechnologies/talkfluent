<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Carbon\Carbon;
class ImageController extends TalkController
{
    function __construct(){
    	$this->model = new Image();
    }
    public function create(Request $request){
    	$data = $request->all();
    	if(isset($data['image'])){
    		$date = Carbon::now()->toDateString();
    		$time = str_replace(':', '_',Carbon::now()->toTimeString());
    		$ext = $request->file('file')->extension();
    		$filename = $date.'_'.$time.'.'.$ext;
    		$result = $request->file('file')->storeAs('images', $filename);
    		$this->model = new Image();
    		$insertData = array(
    			'url'	=> '/storage/view_images/'.$filename
    		);
    		$this->insertDB($insertData);
    		return $this->response();
    	}else{
    		return response()->json(array('data' => null));
    	}
    }
}
