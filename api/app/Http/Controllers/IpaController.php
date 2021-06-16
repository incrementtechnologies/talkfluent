<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ipa;
use App\IpaClassification;
use App\WordAudio;
class IpaController extends TalkController
{
    public $ipaArray = array();
    public $ipaArrayForTitle = array();
    public $accentResponse = '';
    public $ipa = null;
    public $classifications = null;
    public $classificationsTitle = '';
    public $wordDetails = null;

    function __construct(){
      $this->model = new Ipa();
      $this->notRequired = array('audio', 'video', 'image', 'bottom');
    }

    public function manageIpaWord($string, $transcription, $classifications, $wordDetails){
      // $string, $transcription, $classifications
      // $string = 'd,e';
      // $transcription = 'd';
      // $classifications = 'Preposition,Definite article';
      $this->wordDetails = $wordDetails;
      $this->ipa = $string;
      $this->classifications = $this->explodeString($classifications, ',');
      $ipaArrayTemp = $this->explodeString($string, ',');
      $this->ipaArray = $this->replaceStringForBold($ipaArrayTemp);
      $this->ipaArrayForTitle = $this->replaceCodeToTitle($this->ipaArray, $ipaArrayTemp);
      $j = 0;
      foreach ($this->ipaArray as $key) {
        # code...
        // set header
        if($j == 0){
          // set start
          // set title
          // set classifications
          $this->start($transcription);
          $this->definition(0, $this->manageClassification($this->classifications));
        }else{

        }
        $ipaResult = Ipa::where('code', '=', $key)->get();
        if(sizeof($ipaResult) > 0){
          $this->divStart($j + 1);
          if($ipaResult[0]['audio'] && $ipaResult[0]['audio'] !== 'null') {
            $this->desktopAudio($ipaResult[0]['audio'], $j + 1); // get url of ipa
          } else {
            $this->renderNoAudio($ipaResult[0]['audio'], $j + 1);
          }
          $this->ipaTitle($ipaResult[0]['subtitle'], $ipaResult[0]['bottom']); // bottom silenct, consonant. add bottom on database
          $this->mobileAudio($ipaResult[0]['audio'], $j + 1); // get the url of ipa
          $this->image($ipaResult[0]['image'], $ipaResult[0]['video']); // get the image url of ipa
          $this->accentResponse .= $ipaResult[0]['text']; // append ipa
        }
        $j++;
      }
      $this->endResponse();
      return $this->accentResponse;
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

    public function replaceCodeToTitle($arrayClean, $arrayOriginal){
      $j = 0;
      $response = array();
      foreach ($arrayClean as $key) {
        $result = Ipa::where('code', '=', $key)->get();
        if(strpos($arrayOriginal[$j], '</b>')){
          $response[] = '<b>'.((sizeof($result) > 0) ? $result[0]['title'] : null).'</b>';
        }else{
          $response[] = (sizeof($result) > 0) ? $result[0]['title'] : null;
        }
        $j++;
      }
      return $response;
    }

    public function title(){
      $counter = 1;
      $response = '<label>';
      foreach ($this->ipaArrayForTitle as $key) {
        $response .='<a onclick="focusDiv(\'div'.$counter.'\')">'.$key.'</a>';
        $counter++;
      }
      return $response.'</label>';
    }

    public function start($transcription){
      $this->accentResponse = '<span class="accent-item">
          <span class="accent-header-holder">
            <span class="accent-header">
              '.$this->title().'
            </span>
          </span>
          <span class="accent-item-holder">
            <span class="accent-header-extra" id="divA">
              <label>/'.$transcription.'/</label>
            </span>
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

    public function definition($id, $description){
      $this->accentResponse .= '<span class="accent-header-extra-info" style="color: #cccc;margin-top:-5px;" id="divB"></span>';

      // if($this->wordDetails['pronounciation'] != null){
      //   $pBtnId = 'pronounciationAudio' + $id;
      //   $pronounciation = '<p><i class="fa fa-volume-up pronounciation-volume-icons"@click="playerHowler(\''.$this->wordDetails['pronounciation_audio'].'\',\''.$pBtnId.'\')" id="'.$pBtnId.'"></i>';


      //   $this->accentResponse .= '
      //         <i class="fa fa-product-hunt popover_title text-primary" @click="setPronounciationPopOver('.$id.')" style="font-size: 18px;padding-right: 0px;"></i>
      //         <i class="fa fa-info-circle popover_title text-primary" @click="setSentencePopOver('.$id.')"></i>'.$this->classificationsTitle.'
      //         <span v-bind:class="{\'active-popover\': pronounciationPopOverFlag === '.$id.'}" class="pronounce_popover_content">
      //           <span class="header-popover"><i class="fas fa-close" @click="setPronounciationPopOver('.$id.')"></i></span>
      //           '.$pronounciation.$this->wordDetails['pronounciation'].'
      //         </span>
      //         <span v-bind:class="{\'active-popover\': sentencePopOverFlag === '.$id.'}" class="popover_content">
      //         <span class="header-popover"><i class="fas fa-close" @click="setSentencePopOver('.$id.')"></i></span>
      //         '.$description.'
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

    public function renderNoAudio($url, $id) {
      $button = 'basicAudio'.$id.'PlayButton';
      $buttonRepeat = 'basicAudioRepeat'.$id.'PlayButton';
      $this->accentResponse .= '
      <span class="accent-audio show-on-desktop">
        <p></p>
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
