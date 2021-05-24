<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Lesson;
use App\EnabledLesson;
use App\LessonTick;
use App\Content;
use App\TopLesson;
use App\CategoryLesson;
use App\Test;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LessonController extends TalkController
{
    function __construct(){
      $this->model = new Lesson();

      $this->notRequired = array(
        "grammar_tip",
        "grammar_audio",
        "culture_tip",
        "culture_audio",
        "grammar",
        "grammar_test_content",
        "grammar_question",
        "grammary_test_content_audio",
        'video_url'
      );
    }

    // public function create(Request $request){
    //   $data = $request->all();
    //   $lessonFilename = Carbon::now()->toDateString() .'_'.$data['lesson_filename'];
    //   $result = $request->file('lesson_file')->storeAs('lessonAudio', $lessonFilename); 
    //   if(isset($data['audio_filename'])){
    //     $afilename = Carbon::now()->toDateString() .'_'.$data['audio_filename'];
    //     $result = $request->file('accent_audio_file')->storeAs('accentAudio', $afilename); 
    //     $data['accent_audio'] = $afilename;
    //   }
    //   if(isset($data['grammar_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['grammar_filename'];
    //     $result = $request->file('grammar_file')->storeAs('grammarAudio', $afilename); 
    //     $data['grammar_audio'] = $filename;
    //   }
    //   if(isset($data['culture_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['culture_filename'];
    //     $result = $request->file('culture_file')->storeAs('cultureAudio', $afilename); 
    //     $data['culture_audio'] = $filename;
    //   }
    //   // if(isset($data['video_filename'])){
    //   //   $vfilename = Carbon::now()->toDateString() .'_'.$data['video_filename'];
    //   //   $result = $request->file('accent_video_file')->storeAs('accentVideo', $vfilename); 
    //   //   $data['accent_video'] = $vfilename;
    //   // }
    //   else{
    //     //
    //   }
    //   $data['status'] = 'PENDING';
    //   $data['lesson_audio_path'] = '/storage/lesson_audio/';
    //   $data['grammar_audio_path'] = '/storage/grammar_audio/';
    //   $data['culture_audio_path'] = '/storage/culture_audio/';
    //   $data['audio_path'] = '/storage/accent_audio/';
    //   $data['video_path'] = '/storage/accent_video/';
    //   $data['lesson_audio'] = $lessonFilename;
    //   $this->insertDB($data);
    //   return $this->response();
    // }

    // public function update(Request $request){
    //   $data = $request->all();
    //   if(isset($data['lesson_filename'])){
    //     $lessonFilename = Carbon::now()->toDateString() .'_'.$data['lesson_filename'];
    //     $result = $request->file('lesson_file')->storeAs('lessonAudio', $lessonFilename); 
    //     $data['lesson_audio'] = $lessonFilename;
    //     $this->deleteAudioFile($data['id'], 'lessonAudio/', 'lesson_audio');
    //   }
    //   if(isset($data['grammar_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['grammar_filename'];
    //     $result = $request->file('grammar_file')->storeAs('grammarAudio', $filename); 
    //     $data['grammar_audio'] = $filename;
    //     $this->deleteAudioFile($data['id'], 'grammarAudio/', 'grammar_audio');
    //   }
    //   if(isset($data['culture_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['culture_filename'];
    //     $result = $request->file('culture_file')->storeAs('cultureAudio', $filename); 
    //     $data['culture_audio'] = $filename;
    //     $this->deleteAudioFile($data['id'], 'cultureAudio/', 'culture_audio');
    //   }
    //   if(isset($data['audio_filename'])){
    //     $afilename = Carbon::now()->toDateString() .'_'.$data['audio_filename'];
    //     $result = $request->file('accent_audio_file')->storeAs('accentAudio', $afilename); 
    //     $data['accent_audio'] = $afilename;
    //     $this->deleteAudioFile($data['id'], 'accentAudio/', 'accent_audio');
    //   }
    //   // if(isset($data['video_filename'])){
    //   //   $vfilename = Carbon::now()->toDateString() .'_'.$data['video_filename'];
    //   //   $result = $request->file('accent_video_file')->storeAs('accentVideo', $vfilename); 
    //   //   $data['accent_video'] = $vfilename;
    //   //   $this->deleteAudioFile($data['id'], 'accentVideo/', 'accent_video');
    //   // }
    //   else{
    //     //
    //   }
      
    //   $data['lesson_audio_path'] = '/storage/lesson_audio/';
    //   $data['grammar_audio_path'] = '/storage/grammar_audio/';
    //   $data['culture_audio_path'] = '/storage/culture_audio/';
    //   $data['audio_path'] = '/storage/accent_audio/';
    //   $data['video_path'] = '/storage/accent_video/';
    //   $this->model = new Lesson();
    //   $this->updateDB($data);
    //   return $this->response();
    // }
    //  public function deleteAudioFile($id, $path, $column){
    //   $parameter = array( 
    //     "condition" => [array(
    //       "column" => 'id',
    //       "clause" => "=",
    //       "value"  => $id
    //     )]
    //   );
    //   $this->model = new Lesson();
    //   $result = $this->retrieveDB($parameter);
    //   $file = $path.$result[0][$column];
    //   if($result[0][$column] != null){
    //     Storage::delete($file);
    //     return true;
    //   }else{
    //     return false;
    //   }
    // }

    public function dashboard(Request $request){
        // lesson 
        // enabled lessons
        // create list of enabled lessons for where in lessons

        $accountId = $request->all();
        $accountId = $accountId['account_id'];
        $topLesson = TopLesson::select('*')->whereNull('deleted_at')->get();
        $account = Account::where('id', '=', $accountId)->get();
        if(sizeof($topLesson) > 0 && (sizeof($account) > 0 && $account[0]['payment_status'] != 'failed')){
            $i = 0;
            foreach ($topLesson as $key) {

                $categoryLesson = CategoryLesson::where('top_lesson_id', '=', $key->id)->whereNull('deleted_at')->get();
                
                if(sizeof($categoryLesson) > 0){
                    
                    $j = 0;
                    
                    foreach ($categoryLesson as $innerKey) {
                        $lesson = null;   
                        if($account[0]['plan'] == 'pause'){
                            $enabledLessonsArray = $this->getEnabledLessons($accountId);
                             $lesson = Lesson::where('category_lesson_id', '=', $innerKey->id)->whereIn('id', $enabledLessonsArray)->whereNull('deleted_at')->get();
                        }else{
                            $lesson = Lesson::where('category_lesson_id', '=', $innerKey->id)->whereNull('deleted_at')->get();
                        }
                        if(sizeof($lesson) > 0){
                          $k = 0;
                          foreach ($lesson as $key) {
                            if($lesson[$k]['status'] == 'APPROVED' || ($lesson[$k]["status"] == 'PENDING' && $$account[0]['account_type'] == 'ADMIN')){
                                $lesson[$k]["display_status"] = 'success';
                            }else if($lesson[$k]['status'] == 'PENDING' && $account[0]['account_type'] == 'USER'){
                                $lesson[$k]['display_status'] = 'danger';
                            }
                            $testResult = Test::where('account_id', '=', $accountId)->where('lesson_id', '=', $key->id)->get();
                            if(sizeof($testResult) > 0){
                                $lesson[$k]["test_days"] = intVal($testResult[0]['number_of_days']);
                            }else{
                                $lesson[$k]["test_days"] = 0;
                            }
                            $k++;
                          }

                          $categoryLesson[$j]['lesson'] = $lesson; 
                        }else{
                          $categoryLesson[$j]['lesson'] = null; 
                        }
                        $j++;
                    }
                $topLesson[$i++]['category'] = $categoryLesson;
               }else{
                 $topLesson[$i++]['category'] = null;
               }
            }
            return response()->json(array('data' => $topLesson));
        }else{
            return response()->json(array('data' => null));
        }
    }

    public function getEnabledLessons($accountId){
        $result = EnabledLesson::where('account_id', '=', $accountId)->get();
        $response = array();
        if(sizeof($result) > 0){
            $i = 0;
            foreach ($result as $key) {
                $response[$i] = $result[$i]['lesson_id'];
                $i++;
            }
            return $response;
        }
        return null;
    }

    public function retrieveHistory(Request $request){
        $data = $request->all();
        $accountId = $data['account_id'];
        $response = array(
            "data" => null,
            "message" => null
        );
        $result = Test::where('tests.account_id', '=', $accountId)->where('tests.number_of_days', '=', 5)->orderBy('lesson_id', 'ASC')->get();
        if(sizeof($result) > 0){
            $i = 0;
            foreach ($result as $key) {
                $lessonResult = Lesson::where('id', '=', $key->lesson_id)->get();
                if(sizeof($lessonResult) > 0){
                    $contentResult = Content::where('lesson_id', '=', $key->lesson_id)->get();
                    $tickFlagResult = LessonTick::where('lesson_id', '=', $key->lesson_id)->where('account_id', '=', $accountId)->get();
                    $result[$i]['lesson_tick_flag'] = (sizeof($tickFlagResult) > 0) ? $this->getTickFlagValue($tickFlagResult[0]->flag) : false;
                    $result[$i]['contents'] = (sizeof($contentResult) > 0) ? $contentResult : null;
                    $result[$i]['lesson'] = $lessonResult;
                }else{
                    $result[$i]['lesson'] = null;
                    $result[$i]['contents'] = [];
                }
                $i++;
            }
            $response['data'] = $result;
        }else{
            $response['message'] = "Empty";
        }
        return response()->json($response);
    }

    public function getTickFlagValue($flag){
        return (intval($flag) === 0) ? false : true;
    }

}
