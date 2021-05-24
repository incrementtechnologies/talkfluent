<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Save;
use App\WordAudio;
use App\EnabledLesson;
use Illuminate\Support\Facades\DB;
class SaveController extends TalkController
{
    function __construct(){
      $this->model = new Save();
    }

    public function create(Request $request){
      $data = $request->all();
      $lessonId = $data['lesson_id'];
      $tempWordAudioId = $this->checkRootWord($data['word_audio_id']);
      $data['word_audio_id'] = $tempWordAudioId;
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['word_audio_id', '=', $tempWordAudioId]
      );
      $result = Save::where($condition)->get();
      if(sizeof($result) > 0){
        return response()->json(array('data' => null, 'message' => 'Duplicate Entry'));
      }else{
        $this->insertDB($data);
        $enabledLesson = EnabledLesson::where('lesson_id', '=', $lessonId)->where('account_id', '=', $data['account_id'])->get();
        if(sizeof($enabledLesson) <= 0){
          $enabledLesson = new EnabledLesson();
          $enabledLesson->account_id = $data['account_id'];
          $enabledLesson->lesson_id = $lessonId;
          $enabledLesson->save();
        }
        return $this->response();
      }
    }

    public function checkRootWord($wordId){
      // get the word
      $result = WordAudio::where('id', '=', $wordId)->get();
      if(sizeof($result) > 0){
        $word = ' '.$result[0]['word'];
        if(strpos($word, '!') || strpos($word, '¿')){
          // explode then get the word and search
          $word = substr($word, 3);
          $id =  $this->getRootWord($word);
          return ($id != null) ? $id : $wordId;
        }else{
          return $wordId;
        }
      }else{
        return $wordId;
      }
    }

    public function getRootWord($word){
      $result = WordAudio::where('word', '=', $word)->get();
      return (sizeof($result) > 0) ? $result[0]['id'] : null;
    }

    public function retrieveHistory(Request $request){
      $data = $request->all();
      $char = $data['char'];
      $query = '(word_audios.word like "'.$char.'"';
      $query .= ' OR word_audios.word like "<b>'.$char.'"';
      $query .= ' OR word_audios.word like "¡'.$char.'"';
      $query .= ' OR word_audios.word like "¿'.$char.'"';
      $query .= ' OR word_audios.word like "¡<b>'.$char.'"';
      $query .=  ' OR word_audios.word like "¿<b>'.$char.'")';
      $response = DB::table('words_saves')
                  ->where('words_saves.account_id', '=', $data['account_id'])
                  ->where('words_saves.deleted_at', '=', null)
                  ->whereRaw($query)
                  ->leftJoin('word_audios', 'word_audios.id', '=', 'words_saves.word_audio_id')
                  ->orderBy('word_audios.plain', 'asc')
                  ->get();
      return response()->json(array('data' => $response, 'query' => $query));
    }

    public function customRetrieve(Request $request){
      $data = $request->all();
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['word_audio_id', '=', $data['word_audio_id']]
      );
      $result = Save::where($condition)->get();
      if(sizeof($result) > 0){
        return response()->json(array('data' => 1));
      }else{
        return response()->json(array('data' => 0));
      }
    }
}
