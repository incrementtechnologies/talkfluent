<template>
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" v-on:click="redirectDashboard('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
        <li class="breadcrumb-item active">Already Known</li>
      </ul>
    </div>
    <div class="col-lg-10 mx-auto">
      <ul class="pagination pagination-lg pagination-header">
        <li class="page-item page-link page-link2" v-bind:class="{'word-active':isActive.word === true}" v-on:click="redirect('/already_known/', 'word')">Words</li>
        <li class="page-item page-link page-link2" v-bind:class="{'sentence-active':isActive.sentence === true}" v-on:click="redirect('/already_known/', 'sentence')">Sentences</li>
        <li class="page-item page-link page-link2 link3" v-bind:class="{'lesson-active':isActive.lesson === true}" v-on:click="redirect('/already_known/', 'lesson')">Lessons</li>
         <li class="page-item disabled page-item-block">
        </li>
<!--         <li class="guide-video-history">
          <div class="wistia_embed guide-video popover=true wistia_async_u8p9wq6mq8" onclick="pause()"></div>
        </li> -->
      </ul>
    </div>
    <div v-bind:class="{'word-result-active': isActive.word === true}" class="col-lg-10 mx-auto word-result">
      <span class="pagination-holder pagination-mobile-hide">
        <ul class="pagination justify-content-center word-pagination ">
          <li v-bind:class="{'disabled': wordPrevFlag === false}" class="page-item" v-if="active !== null">
            <span class="page-link" v-on:click="wordPrev()">Previous</span>
          </li>
          <li class="page-item disabled" v-if="active === null">
            <span class="page-link">Previous</span>
          </li>
          <li class="page-item page-link" v-bind:class="{'active': (i + 64) === active}" v-for="i in 26" v-html="'&#' + (i + 64) + ';'" v-on:click="sortWord(i), pager(i + 64)"></li>
          <li v-bind:class="{'disabled': wordNextFlag === false}" class="page-item" v-if="active !== null">
            <span class="page-link" v-on:click="wordNext()">Next</span>
          </li>
          <li class="page-item disabled" v-if="active === null">
            <span class="page-link">Next</span>
          </li>
        </ul>
      </span>
      <span class="pagination-holder pagination-mobile-show">
        <ul class="pagination justify-content-center word-pagination">
          <li v-bind:class="{'disabled': wordPrevFlag === false}" class="page-item" v-if="active !== null">
            <span class="page-link" v-on:click="wordMobilePrev()">Previous</span>
          </li>
          <li class="page-item disabled" v-if="active === null">
            <span class="page-link">Previous</span>
          </li>
          <li class="page-item page-link" v-bind:class="{'active': (i + 64 + wordLimitAdder) === active}" v-for="i in wordLimit" v-html="'&#' + (i + 64 + wordLimitAdder) + ';'" v-on:click="sortWord(i + wordLimitAdder), pager(i + 64 + wordLimitAdder)"></li>
          <li v-bind:class="{'disabled': wordNextFlag === false}" class="page-item" v-if="active !== null">
            <span class="page-link" v-on:click="wordMobileNext()">Next</span>
          </li>
          <li class="page-item disabled" v-if="active === null">
            <span class="page-link">Next</span>
          </li>
        </ul>
      </span>
      <table class="table table-hover borderless"  v-if="word.length >= 1">
        <thead>
          <tr class="thead">
            <td>WORD</td>
            <td>TRANSLATION</td>
            <!-- <td>SYLLABLE</td> -->
          </tr>
        </thead>
        <tbody class="table-hover">
          <tr v-for="item, index in word">
            <td>
              <i class="fa fa-volume-up regular-volume-up" @click="audioHandler.playerHowler(config.BACKEND_URL + item.audio, 'audioWord' + index)" v-bind:id="'audioWord' + index"></i>
              <label v-html="item.word" style="text-transform: capitalize;"></label>
            </td>
            <td>
              <label v-html="item.translation" style="padding-top: 10px;text-transform: capitalize;"></label>
            </td>
<!--             <td>
              <i class="fa fa-volume-up regular-volume-up" v-bind:onclick="'playerHowler(\'' + config.BACKEND_URL + item.syllabus_audio + '\',\'syllabusAudioWord' + index + '\')'" v-bind:id="'syllabusAudioWord' + index"></i>
              <label v-html="item.syllabus"></label>
            </td> -->
          </tr>
        </tbody>
      </table>
      <span v-else class="error-container text-danger">No Word(s) Learned or Completed!</span>
    </div>
    <div v-bind:class="{'sentence-result-active': isActive.sentence === true}" class="col-lg-10 mx-auto  sentence-result">
        <span class="pagination-holder pagination-mobile-hide">
        <ul class="pagination justify-content-center word-pagination ">
          <li v-bind:class="{'disabled': sentencePrevFlag === false}" class="page-item" v-if="activeSentence !== null">
            <span class="page-link" v-on:click="sentencePrev()">Previous</span>
          </li>
          <li class="page-item disabled" v-if="activeSentence === null">
            <span class="page-link">Previous</span>
          </li>
          <li class="page-item page-link" v-bind:class="{'active': (i + 64) === activeSentence}" v-for="i in 26" v-html="'&#' + (i + 64) + ';'" v-on:click="sortSentence(i), pagerSentence(i + 64)"></li>
          <li v-bind:class="{'disabled': sentenceNextFlag === false}" class="page-item" v-if="activeSentence !== null">
            <span class="page-link" v-on:click="sentenceNext()">Next</span>
          </li>
          <li class="page-item disabled" v-if="activeSentence === null">
            <span class="page-link">Next</span>
          </li>
        </ul>
      </span>
      <span class="pagination-holder pagination-mobile-show">
        <ul class="pagination justify-content-center word-pagination">
          <li v-bind:class="{'disabled': sentencePrevFlag === false}" class="page-item" v-if="activeSentence !== null">
            <span class="page-link" v-on:click="sentenceMobilePrev()">Previous</span>
          </li>
          <li class="page-item disabled" v-if="activeSentence === null">
            <span class="page-link">Previous</span>
          </li>
          <li class="page-item page-link" v-bind:class="{'active': (i + 64 + sentenceLimitAdder) === activeSentence}" v-for="i in sentenceLimit" v-html="'&#' + (i + 64 + sentenceLimitAdder) + ';'" v-on:click="sortSentence(i + sentenceLimitAdder), pagerSentence(i + 64 + sentenceLimitAdder)"></li>
          <li v-bind:class="{'disabled': sentenceNextFlag === false}" class="page-item" v-if="activeSentence !== null">
            <span class="page-link" v-on:click="sentenceMobileNext()">Next</span>
          </li>
          <li class="page-item disabled" v-if="activeSentence === null">
            <span class="page-link">Next</span>
          </li>
        </ul>
      </span>
      <table class="table table-hover borderless" v-if="sentence.length >= 1">
        <thead>
          <tr class="thead">
            <td>SENTENCE</td>
            <td>TRANSLATION</td>
           <!--  <td>SYLLABLE</td> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="item, index in sentence" >
            <td>
              <i class="fa fa-volume-up regular-volume-up" @click="audioHandler.playerHowler(config.BACKEND_URL + item.audio, 'audioSentence' + index)" v-bind:id="'audioSentence' + index"></i>
              <label v-html="item.original"></label>
            </td>
            <td><label v-html="item.translation" style="padding-top: 10px;"></label></td>
<!--             <td>
              <i class="fa fa-volume-up regular-volume-up" v-bind:onclick="'playerHowler(\'' + config.BACKEND_URL + item.syllabus_audio + '\',\'syllabusAudioSentence' + index + '\')'" v-bind:id="'syllabusAudioSentence' + index"></i>
              <label v-html="item.syllabus"></label>
            </td> -->
          </tr>
        </tbody>
      </table>
      <span v-else class="error-container text-danger">No Sentence(s) Learned or Completed!</span>
    </div>
    <div v-bind:class="{'lesson-result-active': isActive.lesson === true}" class="col-lg-10 mx-auto lesson-result-history">
      <span class="tick-info pull-right">
        <label><i class="fas fa-check"></i>Tick when you have put in your Calendar as a reminder to practice lesson with native speaker.</label>
      </span>
      <span v-if="lesson.length > 0">
        <span v-for="item, index in lesson" class="lesson-holder-history">
         <div class="lesson-item-history" v-bind:id="'lesson-container-' + index"> 
          <i class="fa fa-volume-up regular-volume-up" @click="audioHandler.playerHowler(config.BACKEND_URL + item.lesson[0].lesson_audio, 'audioLessonKnown' + index )" v-bind:id="'audioLessonKnown' + index" style="margin-top:2px; margin-left:2px;"></i>
          <label style="padding-top: 5px; padding-left: 10px;" class="title">{{index + 1}}. 
            <label v-html="item.lesson[0].title"></label>
          </label>
          <i class="fa fa-check test-check-history pull-right" v-on:click="toggle(index)" v-if="item.lesson_tick_flag === true"></i>
          <!-- <i class="fa fa-check test-check-number-history pull-right" v-on:click="toggle(index)" v-else></i> -->
          <!-- <label  class="test-check-number-history pull-right" v-on:click="toggle(index)" v-else>G</label> -->
<!--           <i class="fas fa-toggle-on pull-right tick-toggle" v-on:click="toggle(index)" v-if="item.lesson_tick_flag === true" data-hover="tooltip" data-placement="bottom" title="Google Calendar"></i>
          <i class="fas fa-toggle-off pull-right tick-toggle text-danger" v-on:click="toggle(index)" v-else data-hover="tooltip" data-placement="bottom" title="Google Calendar"></i> -->
          <i class="fa fa-chevron-down pull-right" style="padding-top:15px; padding-right: 10px;" v-on:click="retrieveContent(item.lesson[0].id, index)"></i>
         </div>
          <div class="lessons-content" v-bind:id="'lesson-content-' + index"  v-if="contents.length > 0">
            <span class="content-holder" v-for="itemContent, indexContent in contents">
              <span class="original">
                <label>{{indexContent + 1}}. </label>
                <label v-html="itemContent.original"></label>
              </span>
              <span class="translation">
                <label>{{indexContent + 1}}. </label>
                <label v-html="itemContent.translation"></label>
              </span>  
            </span>
          </div>
        </span>
      </span>
      <span v-else class="error-container text-danger">No Lesson(s) Learned or Completed!</span>
    </div>
  </div>
</template>
<style>

  .word-active, .sentence-active, .lesson-active, .page-link:hover{
    background: #1caceb !important;
    color: white !important;
  }

  .pagination-header{
    border:none !important;
    background: #eee;
    border-radius: 0px !important;

  }
  .pagination-holder{
    width: 100%;
    float: left;
  }

  .page-link:hover{
    cursor: pointer;
  }
  .page-link2{
    width: 15% !important;
    text-align: center !important;
    float: left;
  }
  .page-item-block{
    width: 45% !important;
    float: left;
    border-top-right-radius: 0px !important;
    border-bottom-right-radius: 0px !important;
  }
  .disabled, .page-link2{
    border:none !important;
    background: #eee;
  }
  .guide-video-history{
    float: left;
    width: 10%;
  }
  .guide-video-history .guide-video{
    height: 50px;
    padding-top: 2px;
  }


  /*

      Word Result
  */
  .word-result, .sentence-result, .lesson-result-history{
    display: none;
    float: left;
    min-height: 200px;
    overflow-y: hidden;
  }
  .word-result-active, .sentence-result-active, .lesson-result-active{
    display: block;
  }
  .lesson-item-history{
    height: 50px;
    border-bottom: solid 1px #eee;
    margin-top: 1px;
    font-size: 18px;
  }

  .lesson-item-history i:hover{
    cursor: pointer;
  }
  .lessons-content{
    width: 100%;
    min-height: 50px !important;
    overflow: hidden;
    display: none;
    border-bottom: solid 1px #eee;
    border-right: solid 1px #eee;
    border-left: solid 1px #eee;
    -webkit-transition: width 2s; /* Safari */
    transition: width 2s;
  }
  .lesson-holder-history{
    float: left;
    height: auto;
    width: 100%;
  }
  .content-holder{
    min-height: 50px;
    overflow: hidden;
  }
  .content-holder .original{
    width: 100%;
    float: left;
    font-size: 18px;
  }
  .content-holder .translation{
    width: 90%;
    margin-left: 1%;
    float: left;
    color: #888;
    font-size: 18px;
  }
  .content-holder .original label{
    padding-top: 5px;
    padding-bottom: 5px;
  }
  .content-holder .translation label{
    padding-top: 5px;
  }

  .error-container{
    width: 100% !important;
    float: left;
    text-align: center;
  }
  .table-hover:hover{
    cursor: pointer !important;
  }
  .table{
    font-size: 16px !important;
  }
  .fa-chevron-down{
    color: #1caceb;
  }

  .tick-info{
    width: 100%;
    float: left;
    text-align: right;
  }

  .tick-info i{
    width: 24px;
    height:24px;
    border-radius: 12px;
    color: #fff;
    text-align: center !important;
    margin-right: 5px;
    margin-left: 5px;
    background: #1caceb;
    padding-top: 5px;
  }
  .tick-info label{
    padding-right: 10px;
  }

  .tick-toggle{
    font-size: 40px;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;
    color: #1caceb
  }

  .test-check-history{
    width: 40px;
    height:40px;
    border-radius: 20px;
    color: #fff;
    text-align: center !important;
    margin-right: 5px;
    margin-left: 5px;
    margin-top: 5px;
    background: #1caceb;
    padding-top: 10px;
  }
  .test-check-number-history{
    width: 40px;
    height:40px;
    border-radius: 20px;
    color: #fff;
    text-align: center !important;
    margin-right: 5px;
    margin-left: 5px;
    margin-top: 5px;
    background: #aaa;
    padding-top: 10px;
  }
  .test-check-number-history:hover{
    cursor: pointer;
  }
  .test-check-history{
    background: #1caceb;
    color: #fff;
  }

  .word-pagination .active{
    background: #1caceb;
    color: #fff;
  }
  .pagination-mobile-show{
    display: none;
  }
  .pagination-mobile-hide{
    display: block;
  }
  thead{
    border-bottom: 2px solid #ccc !important;
  }
  table.borderless td,table.borderless th{
     border: none !important;
  }

  .thead{ 
    font-weight: 600 !important;
    font-size: 16px;
  }

  @media screen and (max-width: 991px){
    .pagination-mobile-hide{
      display: none;
    }
    .pagination-mobile-show{
      display: block;
    }
  }
  @media screen and (min-width: 551px) and (max-width: 991px) {
    .guide-video-history{
      width: 10% !important;
    }
    .guide-video-history .guide-video{
      padding-top: 0px;
    }
    .pagination-header .page-link2{
      width: 33% !important;
      text-align: center !important;
      font-size: 14px !important;
      padding-left: 0px !important;
      padding-right: 0px !important;
    }

    .pagination-header .link3{
      width: 34% !important;
    }
    .page-item-block{
      width: 0% !important;
      float: left;
    }
    .tick-info{
      text-align: center;
    }
    .test-check-number-history{
      font-size: 18px !important;
    }
  }

  @media screen and (max-width: 550px) {
    .guide-video-history{
      width: 25% !important;
    }
    .guide-video-history .guide-video{
      padding-top: 0px;
    }
    .pagination-header .page-link2{
      width: 33% !important;
      text-align: center !important;
      font-size: 14px !important;
      padding-left: 0px !important;
      padding-right: 0px !important;
    }
    .pagination-header .link3{
      width: 34% !important;
    }
    .page-item-block{
      width: 0% !important;
      float: left;
    }
    .lesson-item-history .title{
      font-size: 12px;
    }
  }
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import {Howl, Howler} from 'howler'
import * as AudioHandler from '../../components/audio'
export default {
  mounted(){
    this.view(this.$route.params.parameter)
    this.initWistia()
  },
  created(){
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      audio: null,
      config: CONFIG,
      word: [],
      sentence: [],
      lesson: [],
      contents: [],
      prevLessonIndex: null,
      isActive: {
        word: true,
        sentence: false,
        lesson: false
      },
      wordAudio: null,
      parameter: this.$route.params.parameter,
      tickToggle: false,
      wistia: {
        url: 'http://fast.wistia.com/assets/external/E-v1.js',
        charset: 'ISO-8859-1'
      },
      active: null,
      wordPrevFlag: false,
      wordNextFlag: true,
      wordLimit: 5,
      wordLimitAdder: 0,
      activeSentence: null,
      sentencePrevFlag: false,
      sentenceNextFlag: true,
      sentenceLimit: 5,
      sentenceLimitAdder: 0,
      audioHandler: AudioHandler
    }
  },
  methods: {
    initWistia(){
      let script = document.createElement('script')
      script.type = 'text/javascript'
      script.src = this.wistia.url
      script.charset = this.wistia.charset
      document.body.appendChild(script)
    },
    redirectDashboard(parameter){
      ROUTER.push(parameter)
      this.audioHandler.pause()
    },
    redirect(parameter, method){
      this.active = null
      this.activeSentence = null
      ROUTER.push(parameter + method)
      this.view(method)
    },
    view(method){
      if(this.audio !== null){
        this.audio.pause()
      }
      if(method === 'word'){
        this.isActive.word = true
        this.isActive.sentence = false
        this.isActive.lesson = false
      }else if(method === 'sentence'){
        this.isActive.word = false
        this.isActive.sentence = true
        this.isActive.lesson = false
      }else{
        this.isActive.word = false
        this.isActive.sentence = false
        this.isActive.lesson = true
      }
      if(this.isActive.word === true){
        this.retrieveWord('')
      }else if(this.isActive.sentence === true){
        this.retrieveSentence('')
      }else{
        this.retrieveLesson()
      }
    },
    sortWord(i){
      let char = String.fromCharCode(i + 64)
      this.retrieveWord(char)
    },
    pager(index){
      this.active = index
      if(this.active === 90){
        this.wordNextFlag = false
        this.wordPrevFlag = true
      }else if(this.active === 65){
        this.wordNextFlag = true
        this.wordPrevFlag = false
      }else{
        this.wordNextFlag = true
        this.wordPrevFlag = true
      }
    },
    wordNext(){
      if(this.active < 90){
        this.active++
        this.wordNextFlag = true
        this.wordPrevFlag = true
        this.retrieveWord(String.fromCharCode(this.active))
      }
      if(this.active === 90){
        this.wordNextFlag = false
        this.wordPrevFlag = true
      }
    },
    wordPrev(){
      if(this.active > 65){
        this.active--
        this.wordNextFlag = true
        this.wordPrevFlag = true
        this.retrieveWord(String.fromCharCode(this.active))
      }
      if(this.active === 65){
        this.wordNextFlag = true
        this.wordPrevFlag = false
      }
    },
    wordMobileNext(){
      if(this.active < 90){
        this.active++
        this.wordNextFlag = true
        this.wordPrevFlag = true
        this.retrieveWord(String.fromCharCode(this.active))
        if(this.wordLimitAdder < 21){
          this.wordLimitAdder++
        }
      }
      if(this.active === 90){
        this.wordNextFlag = false
        this.wordPrevFlag = true
      }
    },
    wordMobilePrev(){
      if(this.active > 65){
        this.active--
        this.wordNextFlag = true
        this.wordPrevFlag = true
        this.retrieveWord(String.fromCharCode(this.active))
        if(this.wordLimitAdder >= 1){
          this.wordLimitAdder--
        }
      }
      if(this.active === 65){
        this.wordNextFlag = true
        this.wordPrevFlag = false
      }
    },
    retrieveWord(char){
      let parameter = {
        'account_id': this.user.userID,
        'char': char + '%'
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('save/retrieve_history', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        this.word = response.data
      })
    },
    sortSentence(i){
      let char = String.fromCharCode(i + 64)
      this.retrieveSentence(char)
    },
    pagerSentence(index){
      this.activeSentence = index
      if(this.activeSentence === 90){
        this.sentenceNextFlag = false
        this.sentencePrevFlag = true
      }else if(this.activeSentence === 65){
        this.sentenceNextFlag = true
        this.sentencePrevFlag = false
      }else{
        this.sentenceNextFlag = true
        this.sentencePrevFlag = true
      }
    },
    sentenceNext(){
      if(this.activeSentence < 90){
        this.activeSentence++
        this.sentenceNextFlag = true
        this.sentencePrevFlag = true
        this.retrieveSentence(String.fromCharCode(this.activeSentence))
      }
      if(this.activeSentence === 90){
        this.sentenceNextFlag = false
        this.sentencePrevFlag = true
      }
    },
    sentencePrev(){
      if(this.activeSentence > 65){
        this.activeSentence--
        this.sentenceNextFlag = true
        this.sentencePrevFlag = true
        this.retrieveSentence(String.fromCharCode(this.activeSentence))
      }
      if(this.activeSentence === 65){
        this.sentenceNextFlag = true
        this.sentencePrevFlag = false
      }
    },
    sentenceMobileNext(){
      if(this.activeSentence < 90){
        this.activeSentence++
        this.sentenceNextFlag = true
        this.sentencePrevFlag = true
        this.retrieveSentence(String.fromCharCode(this.activeSentence))
        if(this.sentenceLimitAdder < 21){
          this.sentenceLimitAdder++
        }
      }
      if(this.activeSentence === 90){
        this.sentenceNextFlag = false
        this.sentencePrevFlag = true
      }
    },
    sentenceMobilePrev(){
      if(this.activeSentence > 65){
        this.activeSentence--
        this.sentenceNextFlag = true
        this.sentencePrevFlag = true
        this.retrieveSentence(String.fromCharCode(this.activeSentence))
        if(this.sentenceLimitAdder >= 1){
          this.sentenceLimitAdder--
        }
      }
      if(this.activeSentence === 65){
        this.sentenceNextFlag = true
        this.sentencePrevFlag = false
      }
    },
    retrieveSentence(char){
      let parameter = {
        'account_id': this.user.userID,
        char: char + '%'
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('save_content/retrieve_history', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        this.sentence = response.data
      })
    },
    retrieveLesson(){
      let parameter = {
        'account_id': this.user.userID
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('lesson/retrieve_history', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data.length > 0){
          this.lesson = response.data
        }else{
          this.lesson = []
        }
      })
    },
    retrieveContent(id, index){
      this.contents = this.lesson[index].contents
      setTimeout(() => {
        this.makeActive(index)
      }, 5)
      // let parameter = {
      //   'condition': [{
      //     'value': id,
      //     'column': 'lesson_id',
      //     'clause': '='
      //   }]
      // }
      // this.APIRequest('content/retrieve', parameter).then(response => {
      //   if(response.data !== null){
      //     this.contents = response.data
      //   }else{
      //     this.contents = []
      //   }
      // }).done(() => {
      //   setTimeout(() => {
      //     this.makeActive(index)
      //   }, 500)
      // })
    },
    makeActive(index){
      if(this.prevLessonIndex === null){
        this.prevLessonIndex = index
        $('#lesson-container-' + index).css({'background': '#eee', 'color': '#000'})
        // $('#lesson-content-' + index).css({'display': 'block'})
        $('#lesson-content-' + index).slideDown()
      }else{
        if(this.prevLessonIndex === index){
          $('#lesson-content-' + index).slideUp()
          // $('#lesson-content-' + index).css({'display': 'none'})
          $('#lesson-container-' + index).css({'background': '#fff', 'color': '#000'})
          this.prevLessonIndex = null
        }else{
          $('#lesson-content-' + this.prevLessonIndex).slideUp()
          // $('#lesson-content-' + this.prevLessonIndex).css({'display': 'none'})
          $('#lesson-container-' + this.prevLessonIndex).css({'background': '#fff', 'color': '#000'})
          // $('#lesson-content-' + index).css({'display': 'block'})
          $('#lesson-content-' + index).slideDown()
          $('#lesson-container-' + index).css({'background': '#eee', 'color': '#000'})
          this.prevLessonIndex = index
        }
      }
    },
    toggle(index){
      let parameter = {
        'account_id': this.lesson[index].account_id,
        'lesson_id': this.lesson[index].lesson_id,
        'flag': !this.lesson[index].lesson_tick_flag
      }
      this.APIRequest('lesson_ticks/update', parameter).then(response => {
        this.retrieveLesson()
      })
    }
  }
}
</script>
