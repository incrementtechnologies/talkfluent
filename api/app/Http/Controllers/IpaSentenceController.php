<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ipa;
use App\IpaClassification;
use App\WordAudio;
class IpaSentenceController extends TalkController
{
    public $ipaArray = array();
    public $ipaArrayForTitle = array();
    public $ipaArrayForTitleSentence = array();
    public $accentResponse = '';
    public $ipa = null;
    public $classifications = null;
    public $classificationsTitle = '';
    public $charCounter = 0;
    public $wordArray = array();

    function __construct(){
      $this->model = new Ipa();
      $this->notRequired = array('audio', 'video', 'image', 'bottom');
    }

    public function manageIpaSentence($sentence){
      // $sentence = '¿Cómo estás?';
      $this->charCounter = 0;
      // echo $sentence;
      // explode sentence
      // get the word
      // get the ipa of the word 
      $wordArray = $this->explodeString($sentence, ' ');
      $this->manageSentenceTitle($wordArray);
      $j = 0;
      foreach ($wordArray as $value) {
        if($j == 0){
          $this->startSentence();
        }
        if($this->wordArray[$j] != null){
          $this->manageIpaWordForSentence($this->wordArray[$j]['accent_text'], $this->wordArray[$j]['transcription'], $this->wordArray[$j]['classifications'], $this->wordArray[$j]['pronounciation'], $this->wordArray[$j]['pronounciation_audio']);
        }
        $j++;
      }

      $this->endResponse();
      return $this->accentResponse;
    }

    public function manageSentenceTitle($wordArray){
      foreach ($wordArray as $value) {
        $result = $this->getWord($value);
        $this->wordArray[] = $result;
        $ipaArrayTemp = $this->explodeString($result['accent_text'], ',');
        $ipaArray = $this->replaceStringForBold($ipaArrayTemp);
        $charStart = $this->checkInvertedIfExist($value);
        $charEnd = $this->checkIfThereIsEnd($value);
        $this->ipaArrayForTitleSentence[] = $this->replaceCodeToTitle($ipaArray, $ipaArrayTemp, $charStart, $charEnd);
      }
    }

    public function manageIpaWordForSentence($string, $transcription, $classifications, $pronounciation, $pronounciationAudio){
      // $string, $transcription, $classifications
      // $string = 'd,e';
      // $transcription = 'd';
      // $classifications = 'Preposition,Definite article';
      $this->classifications = null;
      $this->classificationsTitle = '';
      $this->ipa = $string;
      $this->classifications = $this->explodeString($classifications, ',');
      $ipaArrayTemp = $this->explodeString($string, ',');
      $this->ipaArray = $this->replaceStringForBold($ipaArrayTemp);
      $j = 0;
      foreach ($this->ipaArray as $key) {
        $ipaResult = Ipa::where('code', '=', $key)->get();
        # code...
        // set header
        if($j == 0){
          $this->definition($this->charCounter + 1, $this->manageClassification($this->classifications), $transcription, $pronounciation, $pronounciationAudio);
        }else{

        }
        if(sizeof($ipaResult) > 0){
          $this->divStart($this->charCounter + 1);
          $this->desktopAudio($ipaResult[0]['audio'], $this->charCounter + 1); // get url of ipa
          $this->ipaTitle($ipaResult[0]['subtitle'], $ipaResult[0]['bottom']); // bottom silenct, consonant. add bottom on database
          $this->mobileAudio($ipaResult[0]['audio'], $this->charCounter + 1); // get the url of ipa
          $this->image($ipaResult[0]['image'], $ipaResult[0]['video']); // get the image url of ipa
          $this->accentResponse .= $ipaResult[0]['text']; // append ipa
        }
        $j++;
        $this->charCounter++;
      }
    }



    public function getWord($value){
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
      $wordAudioResult = null;
      if($exResult != null){
        $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($exResult[$exIndex]))->get();
      }else{
        $wordAudioResult = WordAudio::where('word', '=', $this->splitWordFromInverted($value))->get();
      }
      return sizeof($wordAudioResult) > 0 ? $wordAudioResult[0] : null;
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

    public function checkInvertedIfExist($value){
      if(strpos($value, '¿') > -1){
        return '¿';
      }else if(strpos($value, '¡') > -1){
        return '¡';
      }else{
        return null;
      }
    }

    public function checkIfThereIsEnd($value){
      if(strpos($value,',') > -1){
        return ',';
      }else if(strpos($value, '?') > -1){
        return '?';
      }else if(strpos($value, '.') > -1){
        return '.';
      }else if(strpos($value, '!') > -1){
        return '!';
      }else{
        return null;
      }
    }

    public function explodeString($string, $char){
      if(strpos($string, $char)){
        return explode($char, $string);
      }
      return array($string);
    }

    public function replaceStringForBold($array){
      $response = array();
      foreach ($array as $key) {
        $temp = str_replace("<b>", '', $key);
        $response[] = str_replace('</b>', '', $temp);
      }
      return $response;
    }

    public function replaceCodeToTitle($arrayClean, $arrayOriginal, $charStart, $charEnd){

      $j = 0;
      $response = array();
      foreach ($arrayClean as $key) {
        $result = Ipa::where('code', '=', $key)->get();
        $char = null;
        $status = null;
        if($j == 0){
          $char = $charStart != null ? $charStart : null;
          $status = $charStart != null ? 'start' : null;
        }else if($j == (sizeof($arrayClean) - 1)){
          $char = $charEnd != null ? $charEnd : null; 
          $status = $charEnd != null ? 'end' : null;
        }
        if(strpos($arrayOriginal[$j], '</b>')){
          $response[] = array(
            'content' => '<b>'.((sizeof($result) > 0) ? $result[0]['title'] : null).'</b>',
            'char' => $char,
            'status' => $status
          );
        }else{
          $response[] = array(
            'content' => (sizeof($result) > 0) ? $result[0]['title'] : null,
            'char' => $char,
            'status' => $status
          );
        }
        $j++;
      }
      return $response;
    }

    public function titleSentence(){
      $counter = 1;
      $response = '<span>';
      $upperCaseFlag = false;
      for ($i = 0; $i < sizeof($this->ipaArrayForTitleSentence); $i++) {
        $j = 0; 
        $response .= '<span>';
        foreach ($this->ipaArrayForTitleSentence[$i] as $key) {
          $title = $this->ipaArrayForTitleSentence[$i][$j];
          $title['content'] = ($counter == 1 || $upperCaseFlag == true) ? strtoupper($title['content']) : $title['content'];
          if($title['char'] != null){
            if($title['status'] == 'start'){
              $response .=$title['char'].'<a onclick="focusDiv(\'div'.$counter.'\')">'.$title['content'].'</a>';
              $upperCaseFlag = false;
            }else if($title['status'] == 'end'){
              $response .='<a onclick="focusDiv(\'div'.$counter.'\')">'.$title['content'].'</a>'.$title['char'];
              if($title['char'] != ','){
                $upperCaseFlag = true;
              }
            }
          }else{
            $response .='<a onclick="focusDiv(\'div'.$counter.'\')">'.$title['content'].'</a>';
            $upperCaseFlag = false;
          }
          $j++;
          $counter++;
        }
        $response .= '&nbsp;</span>';
      }
      return $response.'</span>';
    }

    public function startSentence(){
      $this->accentResponse = '<span class="accent-item">
          <span class="accent-header-holder">
            <span class="accent-header">
              '.$this->titleSentence().'
            </span>
          </span>
          <span class="accent-item-holder">
      ';
    }

    public function endResponse(){
      $this->accentResponse .= '
          </span>
        </span>
      ';
    }

    public function manageClassification($classifications){
      $counter = 0;
      $response = '';
      foreach ($classifications as $key) {
        $response .= $this->getClassification($key);
        if($counter == 0){
          $this->classificationsTitle .= $key;
        }else{
          $this->classificationsTitle .= ', '.$key;
        }
        
        $counter++;
      }
      return $response;
    }

    public function getClassification($title){
      $result = IpaClassification::where('title', '=', $title)->get();
      return (sizeof($result) > 0) ? $result[0]['text'] : null;
    }

    public function definition($id, $description, $transcription, $pronounciation, $pronounciationAudio){
      $divId = 'div'.(($id > 1) ? $id : 'A');
      $this->accentResponse .= '
        <span class="accent-header-extra" id="'.$divId.'">
          <label>/'.$transcription.'/</label>
        </span>
          <span class="accent-header-extra-info" style="color: #cccc;margin-top:-5px;" id="divB">
          </span>
        ';
      // if($pronounciation != null){
      //   $pBtnId = 'pronounciationAudio' + $id;
      //   $pronounciationIcon = '<p><i class="fa fa-volume-up pronounciation-volume-icons"@click="playerHowler(\''.$pronounciationAudio.'\',\''.$pBtnId.'\')" id="'.$pBtnId.'"></i>';

      //   $this->accentResponse .= '
      //         <i class="fa fa-product-hunt popover_title text-primary" @click="setPronounciationPopOver('.$id.')" style="font-size: 18px;padding-right: 0px;"></i>
      //         <i class="fa fa-info-circle popover_title text-primary" @click="setSentencePopOver('.$id.')"></i>'.$this->classificationsTitle.'
      //         <span v-bind:class="{\'active-popover\': pronounciationPopOverFlag === '.$id.'}" class="pronounce_popover_content">
      //           <span class="header-popover"><i class="fas fa-close" @click="setPronounciationPopOver('.$id.')"></i></span>
      //         '.$pronounciationIcon.$pronounciation.'
      //         </span>
      //         <span v-bind:class="{\'active-popover\': sentencePopOverFlag === '.$id.'}" class="popover_content">
      //         <span class="header-popover"><i class="fas fa-close" @click="setSentencePopOver('.$id.')"></i></span>
      //       '.$description.'
      //       </span>  
      //     </span>
      //   ';
      // }else{
      //   $this->accentResponse .= '
      //         <i class="fa fa-info-circle popover_title text-primary" @click="setSentencePopOver('.$id.')"></i>'.$this->classificationsTitle.'
      //         <span v-bind:class="{\'active-popover\': sentencePopOverFlag === '.$id.'}" class="pronounce_popover_content">
      //         <span class="header-popover"><i class="fas fa-close" @click="setSentencePopOver('.$id.')"></i></span>
      //       '.$description.'
      //       </span>  
      //     </span>
      //   ';
      // }
    }

    public function divStart($id){
      $this->accentResponse .= '
      <span class="accent-item" id="div'.$id.'">
        <span class="accent-content">
          <span class="accent-content-left">
      ';
    }

    public function desktopAudio($url, $id){
      $button = 'basicAudio'.$id.'PlayButton';
      $buttonRepeat = 'basicAudioRepeat'.$id.'PlayButton';
      $this->accentResponse .= '
      <span class="accent-audio show-on-desktop">
        <i class="fa fa-volume-up small-volume-icons" @click="playerHowler(\''.$url.'\',\''.$button.'\')" id="'.$button.'"></i>
        <br>
        <i class="fa fa-sync small-volume-icons" @click="playerHowler(\''.$url.'\',\''.$buttonRepeat.'\')" id="'.$buttonRepeat.'" style="margin-top: 5px;"></i>
      </span>';
    }

    public function ipaTitle($ipa, $bottom){
      if($bottom != null && $bottom != '' && $bottom != 'NONE'){
        $this->accentResponse .= '
          <span class="accent-letter">
            <label>'.$ipa.'</label>
            <label class="bottom"><b>('.$bottom.')</b></label>
          </span>
        ';
      }else{
        $this->accentResponse .= '
          <span class="accent-letter">
            <label>'.$ipa.'</label>
          </span>
        ';
      }
    }

    public function mobileAudio($url, $id){
      $button = 'basicAudio'.$id.'PlayButtonMobile';
      $buttonRepeat = 'basicAudioRepeat'.$id.'PlayButtonMobile';
      $this->accentResponse .= '
      <span class="accent-audio show-on-mobile">
        <i class="fa fa-volume-up small-volume-icons" @click="playerHowler(\''.$url.'\',\''.$button.'\')" id="'.$button.'"></i>
        <br>
        <i class="fa fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler(\''.$url.'\',\''.$buttonRepeat.'\')" id="'.$buttonRepeat.'" style="margin-top: 5px;"></i>
      </span>';
    }

    public function image($imageUrl, $videoUrl){
      if($videoUrl != null && $videoUrl !== '' && $videoUrl != 'NONE'){
        $this->accentResponse .= '
            <span class="accent-media-holder">
              <img src="'.$imageUrl.'" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage(\''.$imageUrl.'\')">
              <div class="wistia_embed popover=true accent-media-item '.$videoUrl.'" onclick="pause()"></div>
            </span>
          </span>
        ';
      }else if($imageUrl != null && $imageUrl !== '' && $imageUrl != 'NONE'){
        $this->accentResponse .= '
            <span class="accent-media-holder">
              <img src="'.$imageUrl.'" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage(\''.$imageUrl.'\')">
            </span> 
          </span>
        ';
      }else{
        $this->accentResponse .= '
            <span class="accent-media-holder">
            </span> 
          </span>
        ';
      }
    }

}
