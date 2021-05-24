<?php

namespace App\Http\Controllers;

use App\AccountProfilePicture;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AccountProfilePictureController extends TalkController
{
    function __construct(){
        $this->model = new AccountProfilePicture();
    }
    public function create(Request $request){
      $data = $request->all();
      $url = null;

      if(isset($data['file_url'])){
        $date = Carbon::now()->toDateString();
        $time = str_replace(':', '_', Carbon::now()->toTimeString());
        $ext = $request->file('file')->extension();
        $filename = $data['account_id'].'_'.$date.'_'.$time.'.'.$ext;
        $result = $request->file('file')->storeAs('profiles', $filename);
        $url = '/storage/profile/'.$filename;
        $array = array(
          'account_id'  => $data['account_id'],
          'url'         => $url
        );
        $this->insertDB($array);
      }
      return $this->response();
    }
}
