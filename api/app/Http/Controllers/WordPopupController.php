<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WordPopup; 
use App\WordAudio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\SaveContent;

class WordPopupController extends TalkController
{
    function __construct(){
      $this->model = new WordPopup();
    }

    public function dashboard(Request $request){
      $sentenceResult = null;
      $data = $request->all();
      $saveContentResult = ($data['save_content'] == 1 || $data['save_content'] == '1') ? $this->saveContent($data) : false;
      $result = Answer::where($data['column'], $data['clause'], $data['value'])->get();
      if(sizeof($result) > 0){
        //Split Sentence
        $i = 0;
        foreach ($result as $key) {
          $sentenceAtInitial = $key->original;
          $list = null;
          $j = 0;
          if(strpos($sentenceAtInitial, ' ')){
            $list = explode(' ', $sentenceAtInitial);
            foreach ($list as $value) {
              $exResult = null;
              $exIndex = 0;
              $char = null;
              $newValue = null;
              if(strpos($value,',')){
                $exResult = explode(',', $value);
                $char = ',';
                $newValue = $exResult[0];
              }else if(strpos($value, '?')){
                $exResult = explode('?', $value);
                $char = '?';
                $newValue = $exResult[0];
              }else if(strpos($value, '.')){
                $exResult = explode('.', $value);
                $char = '.';
                $newValue = $exResult[0];
              }else if(strpos($value, '!')){
                $exResult = explode('!', $value);
                $char = '!';
                $newValue = $exResult[0];
              }else{
                $char = '';
                $newValue = $value;
              }
              $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($newValue))->get();
              $translation = null;
              if(sizeof($wordAudioResult) > 0){
                $translation = $wordAudioResult[0]->translation;
              }else{
                $translation = "Not Found";
              }
              $sentenceResult[$i][$j++] = array('word' => $newValue, 'char' => $char, "translation" => $translation); 
            }
          }
          else{
            $char = null;
            $newValue = null;
            $exResult = null;
            if(strpos($sentenceAtInitial, '?')){
              $exResult = explode('?', $sentenceAtInitial);
              $char = '?';
              $newValue = $exResult[0];
            }else if(strpos($sentenceAtInitial, '.')){
              $exResult = explode('.', $sentenceAtInitial);
              $char = '.';
              $newValue = $exResult[0];
            }else if(strpos('!', $sentenceAtInitial)){
              $exResult = explode('!', $sentenceAtInitial);
              $char = '!';
              $newValue = $exResult[0];
            }else{
              $char = '';
              $newValue = $sentenceAtInitial;
            }
            $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($newValue))->get();
            $translation = null;
            if(sizeof($wordAudioResult) > 0){
              $translation = $wordAudioResult[0]->translation;
            }else{
              $translation = "Not Found";
            }
            $sentenceResult[$i][$j++] = array('word' => $newValue, 'char' => $char, "translation" => $translation); 
          }
          $i++;
        }
      }else{
        return response()->json(array('data' => null, 'message' => 'Content not found!', 'content_array' => null, 'save_content' => null));
      }
      return response()->json(array('data' => $result, 'message' => null, 'content_array' => $sentenceResult, 'save_content' => $saveContentResult));
    }

    public function saveContent($data){
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['content_id', '=', $data['content_id']]
      );
      $result = SaveContent::where($condition)->get();
      if(sizeof($result) > 0){
        return false;
      }else{
        $newData = array(
          "content_id"  => $data["content_id"],
          "account_id"  => $data["account_id"],
          "created_at"  => Carbon::now()
        );
        SaveContent::insert($newData);
        return 1;
      }
    }

    public function splitWordFromInverted($value){
      if(strpos($value, '¿') > -1){
        $exResult = explode('¿', $value);
        return $exResult[1];
      }else if(strpos($value, '¡') > -1){
        $exResult = explode('¡', $value);
        return $exResult[1];
      }else{
        return $value;
      }
    }
}
