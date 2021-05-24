<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AudioFile;
use Carbon\Carbon;

class AudioFileController extends TalkController
{
    function __construct(){
      $this->model = new AudioFile();
    }
    public function create(Request $request){
      $data = $request->all();
      // $audioFilename = Carbon::now()->toDateString() .'_'.$data['filename'];
      $audioFilename = $data['filename'];
      $result = $request->file('audio_file')->storeAs('audioFiles', $audioFilename); 
      $data['filename'] = $audioFilename;
      $data['url'] = '/storage/audio_files/';
      $this->insertDB($data);
      return $this->response();
    }

    public function update(Request $request){
      $data = $request->all();
      if(isset($data['filename'])){
        // $audioFilename = Carbon::now()->toDateString() .'_'.$data['filename'];
        $audioFilename = $data['filename'];
        $result = $request->file('audio_file')->storeAs('audioFiles', $audioFilename); 
        $data['filename'] = $audioFilename;
        // $this->deleteAudioFile($data['id'], 'audioFiles/', 'filename');
      }
      else{
        //
      }
      $data['url'] = '/storage/audio_files/';
      $this->model = new AudioFile();
      $this->updateDB($data);
      return $this->response();
    }
     public function deleteAudioFile($id, $path, $column){
      $parameter = array( 
        "condition" => [array(
          "column" => 'id',
          "clause" => "=",
          "value"  => $id
        )]
      );
      $this->model = new AudioFile();
      $result = $this->retrieveDB($parameter);
      $file = $path.$result[0]['filename'];
      echo $file;
      if($result[0]['filename'] != null){
        Storage::delete($file);
        return true;
      }else{
        return false;
      }
    }
}
