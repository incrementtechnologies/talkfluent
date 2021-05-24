<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\WordAudio;
use App\Save;
use App\SaveContent;
class ContentController extends TalkController
{
    function __construct(){
      $this->model = new Content();
      $this->notRequired = array(
        "accent_text", "video_url", "plain"
      );
    }

    public function dashboard(Request $request){
      $sentenceResult = array(
        array()
      );
      $data = $request->all();
      $saveContent = null;
      $result = Content::where($data['column'], $data['clause'], $data['value'])->get();
      $content2d = $this->content2DArray($result);
      if(sizeof($result) > 0){
        $customResponse = array('data' => $result);
        //Split Sentence
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['accent_text'] = app('App\Http\Controllers\IpaSentenceController')->manageIpaSentence($key->original);
          $sentenceAtInitial = $key->original;
          $list = null;
          if(strpos($sentenceAtInitial, ' ') > -1){
            $list = explode(' ', $sentenceAtInitial);
            $j = 0;
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
              $wordAudioResult = null;
              if($exResult != null){
                $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($exResult[$exIndex]))->get();
              }else{
                $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($value))->get();
              }
              if(sizeof($wordAudioResult) > 0){
                $wordRootAudioId =  app('App\Http\Controllers\SaveController')->checkRootWord($wordAudioResult[0]->id);
                $saveCondition = array(
                  array('account_id', '=', $data['account_id']),
                  array('word_audio_id', '=', $wordRootAudioId)
                );
                $saveResult = Save::where($saveCondition)->get();
                $click = (sizeof($saveResult) > 0) ? 1 : 0;
                $sentenceResult[$i][$j++] = array('word' => $newValue, 'click' => $click, 'char' => $char, 'translation' => $wordAudioResult[0]->translation, 'audio' => $wordAudioResult[0]->audio);
              }else{
                $sentenceResult[$i][$j++] = array('word' => $newValue, 'click' => 0, 'char' => $char, 'translation' => "Not Found", 'audio' => null);
              }
            }
          }else{
            $newValue = null;
            $char = null;
            $exResult = null;
            if(strpos($sentenceAtInitial, '?') > -1){
                $exResult = explode('?', $sentenceAtInitial);
                $char = '?';
                $newValue = $exResult[0];
              }else if(strpos($sentenceAtInitial, '.') > -1){
                $exResult = explode('.', $sentenceAtInitial);
                $char = '.';
                $newValue = $exResult[0];
              }else if(strpos($sentenceAtInitial, '!') > -1){
                $exResult = explode('!', $sentenceAtInitial);
                $char = '!';
                $newValue = $exResult[0];
              }else{
                $char = '';
                $newValue = $sentenceAtInitial;
              }
              $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($newValue))->get();
              if(sizeof($wordAudioResult) > 0){
                $saveCondition = array(
                  array('account_id', '=', $data['account_id']),
                  array('word_audio_id', '=', $wordAudioResult[0]->id)
                );
                $saveResult = Save::where($saveCondition)->get();
                $click = (sizeof($saveResult) > 0) ? 1 : 0;
                $sentenceResult[$i][] = array('word' => $newValue, 'click' => $click, 'char' => $char, 'translation' => $wordAudioResult[0]->translation, 'audio' => $wordAudioResult[0]->audio);
              }else{
                $sentenceResult[$i][] = array('word' => $newValue, 'click' => 0, 'char' => $char, 'translation' => "Not Found", 'audio' => null);
              }
          }
          $i++;
        }
      }else{
        return response()->json(array('data' => null, 'message' => 'Content not found!', 'content' => null, 'content_array' => null));
      }
      $i = 0;
      foreach ($result as $key) {
        $result[$i++]['saved'] = $this->checkSaveContent($key->id, $data['account_id']);
      }
      return response()->json(array('data' => $sentenceResult, 'message' => null, 'content' => $result, 'content_array' => $content2d));
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

    public function checkSaveContent($contentId, $accountId){
      $condition = array(
        array('content_id', '=', $contentId),
        array('account_id', '=', $accountId)
      );
      $result = SaveContent::where($condition)->get();
      return (sizeof($result) > 0) ? 1 : 0;
    }
    public function content2DArray($result){
      $sentenceResult = null;
      if(sizeof($result) > 0){
        //Split Sentence
        $i = 0;
        foreach ($result as $key) {
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
            //
          }
          $i++;
        }
      }else{
        return null;
      }
      return $sentenceResult;
    }

}
