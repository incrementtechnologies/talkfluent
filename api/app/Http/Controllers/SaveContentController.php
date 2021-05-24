<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SaveContent;
use App\WordAudio;
use Illuminate\Support\Facades\DB;
class SaveContentController extends TalkController
{
    function __construct(){
      $this->model = new SaveContent();
    }


    public function create(Request $request){
      $data = $request->all();
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['content_id', '=', $data['content_id']]
      );
      $result = SaveContent::where($condition)->get();
      if(sizeof($result) > 0){
        return response()->json(array('data' => null, 'message' => 'Duplicate Entry'));
      }else{
        $this->insertDB($data);
        return $this->response();
      }
    }

    public function retrieveHistory(Request $request){
      $data = $request->all();
      $char = $data['char'];
      //char done
      //<b>char done
      //inverted !char
      //inverted ?char
      //inverted !<b>char done
      //inverted ?<b>char done
      $query = '(contents.original like "'.$char.'"';
      $query .= ' OR contents.original like "<b>'.$char.'"';
      $query .= ' OR contents.original like "¡'.$char.'"';
      $query .= ' OR contents.original like "¿'.$char.'"';
      $query .= ' OR contents.original like "¡<b>'.$char.'"';
      $query .=  ' OR contents.original like "¿<b>'.$char.'")';
      $response = DB::table('save_contents')
                  ->where('save_contents.account_id', '=', $data['account_id'])
                  ->where('save_contents.deleted_at', '=', null)
                  ->whereRaw($query)
                  ->leftJoin('contents', 'contents.id', '=', 'save_contents.content_id')
                  ->orderBy('contents.plain', 'asc')
                  ->get();
      return response()->json(array('data' => $response));
    }

    public function customRetrieve(Request $request){
      $data = $request->all();
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['content_id', '=', $data['content_id']]
      );
      $result = SaveContent::where($condition)->get();
      if(sizeof($result) > 0){
        return response()->json(array('data' => 1));
      }else{
        return response()->json(array('data' => 0));
      }
    }
}
