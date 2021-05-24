<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WordAudio;
use App\WordPopup;
use App\Save;
use App\WordImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
class WordAudioController extends TalkController
{
    function __construct(){
      $this->model = new WordAudio();
      $this->validation =  array(
        "word" => "unique:word_audios"
      ); 
      $this->notRequired = array(
        "accent_text", "transcription", "classifications", "pronounciation", "pronounciation_audio", "video_url"
      );
    }
    public function dashboard(Request $request){
      $data = $request->all();
      $result = WordAudio::where($data['column'], $data['clause'], $this->splitWordFromInverted($data['value']))->get();
      if(sizeof($result) > 0){
        $sentenceResult = null;
        //Split Sentence
        $result[0]['accent_text'] = app('App\Http\Controllers\IpaController')->manageIpaWord($result[0]['accent_text'], $result[0]['transcription'], $result[0]['classifications'], $result[0]);
        $wordPopupResult = WordPopup::where('word_audio_id', '=', $result[0]->id)->get();
        $wordImages = WordImage::where('word_audio_id', '=', $result[0]->id)->get();
        $wordImages = (sizeof($wordImages) > 0) ? $wordImages : null;
        if(sizeof($wordPopupResult) > 0){
            $i = 0;
            foreach ($wordPopupResult as $key) {
              $sentenceAtInitial = $key->original;
              $list = null;
              $j = 0;
              if(strpos($sentenceAtInitial, ' ') > -1){
                $list = explode(' ', $sentenceAtInitial);
                foreach ($list as $value) {
                  $exResult = null;
                  $exIndex = 0;
                  $char = null;
                  $newValue = null;
                  if(strpos($value,',') > -1){
                    $exResult = explode(',', $value);
                    $char = ',';
                    $newValue = $exResult[0];
                  }else if(strpos($value, '?') > -1){
                    $exResult = explode('?', $value);
                    $char = '?';
                    $newValue = $exResult[0];
                  }else if(strpos($value, '.') > -1){
                    $exResult = explode('.', $value);
                    $char = '.';
                    $newValue = $exResult[0];
                  }else if(strpos($value, '!') > -1){
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
                $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($sentenceAtInitial))->get();
                $translation = null;
                if(sizeof($wordAudioResult) > 0){
                    $translation = $wordAudioResult[0]->translation;
                }else{
                    $translation = "Not Found";
                }
                $sentenceResult[$i][$j++] = array('word' => $sentenceAtInitial, 'char' => '', "translation" => $translation);
              }
              $i++;
            }
        }
        return response()->json(array('data' => $result, 'message' => null, 'content' => $sentenceResult, 'word' => $wordPopupResult, 'word_images' => $wordImages)); 
      }else{
        return response()->json(array('data' => null, 'message' => 'Content not found!', 'content' => null, 'save_content' => null, 'word' => null, 'word_images' => null));
      }
    }

    public function retrieveByPagination(Request $request){
      $data = $request->all();
      $result = WordAudio::where('word', 'like', $data['title'].'%')->orWhere('word', 'like', $data['alternative'].'%')->get();
      // if(sizeof($result) > 0){
      //   $i = 0;
      //   foreach ($result as $key) {
      //     $result[$i]['accent_text'] = app('App\Http\Controllers\IpaController')->manageIpaWord($result[$i]['accent_text'], $result[$i]['transcription'], $result[$i]['classifications']);
      //     $i++;
      //   }
      // }
      return response()->json(
        array(
          'data'  => $result,
          'error' => null,
          'timestamps'  => Carbon::now()
        )
      );
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
