<template>
  <div class="container" id="testView">
    <span class="audio-days">
      <span class="audio-container" v-if="lesson !== null">
        <span class="test-header">SPANISH</span>
        <span class="content">
          <i @click="playHowler(config.BACKEND_URL + lesson.lesson_audio, 'testSpanishButton')" id="testSpanishButton" v-bind:class="{'text-danger': lesson.lesson_audio === null}" class="fa fa-play fa-volume-up-test"></i>
          <i @click="pauseHowler(config.BACKEND_URL + lesson.lesson_audio, 'testSpanishPauseButton')" id="testSpanishPauseButton" v-bind:class="{'text-danger': lesson.lesson_audio === null}" class="fa fa-pause fa-volume-up-test"></i>
          <i @click="playHowler(config.BACKEND_URL + lesson.lesson_audio, 'testSpanishButtonRepeat')" id="testSpanishButtonRepeat" v-bind:class="{'text-danger': lesson.lesson_audio === null}" class="fas fa-sync fa-volume-up-test repeat-audio-icon-ios"></i>
        </span>
      </span>
      <span class="audio-container audio-container-right" v-if="lesson !== null">
        <span class="test-header">ENGLISH</span>
        <span class="content">
          <i @click="playHowler(config.BACKEND_URL + lesson.english_audio, 'testEnglishButton')" id="testEnglishButton" v-bind:class="{'text-danger': lesson.english_audio === null}" class="fa fa-play fa-volume-up-test"></i>
          <i @click="pauseHowler(config.BACKEND_URL + lesson.english_audio, 'testEnglishPauseButton')" id="testEnglishPauseButton" v-bind:class="{'text-danger': lesson.english_audio === null}" class="fa fa-pause fa-volume-up-test"></i>
          <i @click="playHowler(config.BACKEND_URL + lesson.english_audio, 'testEnglishButtonRepeat')" id="testEnglishButtonRepeat" v-bind:class="{'text-danger': lesson.english_audio === null}" class="fas fa-sync fa-volume-up-test repeat-audio-icon-ios"></i>
        </span>
      </span>
      <!-- <span class="audio-container audio-container-grammar audio-container-right" v-if="lesson !== null && lesson.grammar !== null && lesson.grammar !== 'NONE'">
        <span class="test-header">GRAMMAR</span>
        <span class="content">
          <i @click="playHowler(config.BACKEND_URL + lesson.grammar, 'testGrammarButton')" id="testGrammarButton" v-bind:class="{'text-danger': lesson.grammar === null}" class="fa fa-play fa-volume-up-test"></i>
          <i @click="pauseHowler(config.BACKEND_URL + lesson.grammar, 'testGrammarPauseButton')" id="testGrammarPauseButton" v-bind:class="{'text-danger': lesson.grammar === null}" class="fa fa-pause fa-volume-up-test"></i>
          <i @click="playHowler(config.BACKEND_URL + lesson.grammar, 'testGrammarButtonRepeat')" id="testGrammarButtonRepeat" v-bind:class="{'text-danger': lesson.grammar === null}" class="fas fa-sync fa-volume-up-test repeat-audio-icon-ios"></i>
          <i v-bind:class="{'question-active': grammarQuestionFlag === true}" class="fa fa-question fa-volume-up-test" v-if="lesson.grammar_question !== null" @click="grammarQuestionFlag = !grammarQuestionFlag"></i>
          <span class="question-dropdown" v-if="lesson.grammar_question !== null && grammarQuestionFlag === true">
            <span class="question-header"><i class="fas fa-close" @click="grammarQuestionFlag = false"></i></span>
            <span class="question-content">
              <label v-html="lesson.grammar_question"></label>
            </span>
          </span>
        </span>
      </span>  -->
      <span class="days-container">
        <span class="test-header">DAYS OF TESTING</span>
        <span class="content">
          <label class="test-check" v-bind:class="'check-button-' + i" v-for="i in days" v-if="days > 0" v-on:click="toggle(false)"><i class="fa fa-check"></i></label>
          <label  class="test-check-number" v-bind:class="'uncheck-button-' + (i + days)" v-for="i in remDays" v-on:click="toggle(true)">{{i + days}}</label>
        </span>
      </span>
    </span>
    <span class="text-editor">
      <span class="buttons-holder">
        <span class="editor-button" v-on:click="clear()">
          <i class="fa fa-eraser"></i>
          <label>Clear</label>
        </span>
        <span class="editor-button" v-on:click="undo()">
          <i class="fa fa-rotate-left"></i>
          <label>Undo</label>
        </span>
        <span class="editor-button" v-on:click="redo()">
          <i class="fa fa-rotate-right"></i>
          <label>Redo</label>
        </span>
      </span>
      <span class="buttons-holder">
        <span class="editor-button-char" v-for="item, index in char" v-on:click="insert(item.value)">
          <label>{{item.value}}</label>
        </span>
      </span>
      <span class="text-content">
        <textarea class="text-area" id="textArea" v-model="textAreaInput" v-on:keypress="typing()" v-on:keydown="backspace($event)" v-on:keyup="enter($event)" style="font-size: 20px"></textarea>
      </span>
      <span class="check-button">
        <button class="btn btn-warning" v-on:click="activate('lesson')"><i class="fa fa-check"></i> Lesson</button>
        <!-- <button class="btn btn-primary" v-on:click="activate('grammar')" v-if="lesson !== null && lesson.grammary_test_content_audio !== null && lesson.grammary_test_content_audio !== 'NONE'"><i class="fa fa-check"></i> Grammar</button>
        <span v-if="lesson !== null && lesson.grammary_test_content_audio !== null && lesson.grammary_test_content_audio !== 'NONE'">
          <i @click="playHowler(config.BACKEND_URL + lesson.grammary_test_content_audio, 'testGrammarAudioButton')" id="testGrammarAudioButton" v-bind:class="{'text-danger': lesson.grammar === null}" class="fa fa-play fa-volume-up-test"></i>
          <i @click="pauseHowler(config.BACKEND_URL + lesson.grammary_test_content_audio, 'testGrammarAudioPauseButton')" id="testGrammarAudioPauseButton" v-bind:class="{'text-danger': lesson.grammary_test_content_audio === null}" class="fa fa-pause fa-volume-up-test"></i>
          <i @click="playHowler(config.BACKEND_URL + lesson.grammary_test_content_audio, 'testGrammarAudioButtonRepeat')" id="testGrammarAudioButtonRepeat" v-bind:class="{'text-danger': lesson.grammary_test_content_audio === null}" class="fas fa-sync fa-volume-up-test repeat-audio-icon-ios"></i>  
        </span> -->
      </span>
      <span class="text-content" v-if="content.length > 0">
        <span class="lesson-content" v-for="item, index in content">
          <span class="item">
            <label class="number number-black">{{index + 1}}.</label>
            <span class="text" v-html="item.original"></span>
          </span>
          <span class="item">
            <label class="number" style="color: #ccc;">{{index + 1}}.</label>
            <span class="text"  style="color: #ccc;" v-html="item.translation"></span>
          </span>  
        </span>
      </span>
      <span class="text-content" id="grammarResult" v-if="grammar !== null" v-html="grammar">
      </span>
    </span>
  </div>
</template>
<style>

.audio-days{
  width: 100%;
  min-height: 50px;
  overflow-y: hidden;
  float: left;
}
.audio-container{
  width: 15%;
  float: left;
  height: 75px;
}
.audio-container-grammar{
  width: 20%;
  float: left;
  height: 75px;
}
.audio-container-right{
  margin-left: 5%;
}
.days-container{
  height: 75px;
  width: 35%;
  float: left;
  margin-left: 5%;
}
.audio-container .test-header, .days-container .test-header{
  font-weight: 600;
  text-transform: uppercase;
  width: 100%;
  height: 25px;
  float: left;
}
.audio-container .content, .audio-container .test-header{
  text-align: center;
}
.audio-container .content, .days-container .content{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}
.test-check-number, .test-check{
  padding-top: 10px;
  width: 40px;
  height:40px;
  border-radius: 20px;
  color: #fff;
  text-align: center;
  margin-right: 7px;
  background: #555;
}
.test-check{
  background: #1caceb;
  color: #fff;
}
/*.test-check-number:hover{
  background: #1caceb;
  cursor: pointer;
  color: #fff;
}
.test-check:hover{
  background: #888;
  color: #fff;
  cursor: pointer;
}*/

.fa-volume-up-test{
  color: #1caceb;
  font-size: 24px;
  background: inherit;
  border: solid 2px #1caceb;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  padding-top: 10px;
  text-align: center;
}
.fa-volume-up-test:hover{
  cursor: pointer;
  background: #1caceb;
  color: #fff;
}


/*

  TEXT EDITOR
  
*/
.text-editor{
  height: auto;
  width: 100%;
  float: left;
}
.buttons-holder{
  width: 100%;
  min-height: 50px;
  overflow-y: hidden;
  float: left;
  margin-top: 5px;
}
.text-content{
  width: 100%;
  min-height: 302px;
  overflow-y: hidden; 
  margin-top: 5px;
  float: left;
  border: solid 1px #1caceb;
}
.text-area{
  min-height: 300px;
  max-height: 300px;
  border: none;
  width: 100%;
  float: left;
}
.check-button{
  height: 50px;
  width: 100%;
  float: left;
  margin-top: 20px; 
  margin-bottom: 20px;
}

.check-button button{
  margin-bottom: 10px;
}
  
.editor-button{
  height: 50px;
  width: 5%;
  border-radius: 5px;
  float: left;
  margin-left: 2px;
  border: solid 1px #ddd;
  color: #1caceb;
}
.editor-button i{
  width: 100%;
  height: 30px;
  font-size: 24px;
  padding-top: 6px;
  float: left;
  text-align: center;
}
.editor-button label{
  width: 100%;
  height: 20px;
  font-size: 10px;
  padding-top: 5px;
  font-weight: 600;
  float: left;
  text-align: center;
  text-transform: uppercase;
}

.editor-button:hover, .editor-button-char:hover{
  border: solid 1px #1caceb;
  cursor: pointer;
}

.editor-button label:hover, .editor-button i:hover, .editor-button-char label:hover{
  cursor: pointer;  
}

.editor-button:active, .editor-button-char:active{
  background: #1caceb;
  color: #fff;
}

.editor-button-char{
  height: 35px;
  width: 4%;
  margin-left: 2px;
  float: left;
  border: solid 1px #ddd;
  border-radius: 5px;
  color: #1caceb;
  text-align: center;
}
.editor-button-char label{
  font-size: 16px;
  padding-top: 5px;
  font-weight: 600;
}
.lesson-content{
  width: 100%;
  min-height: 50px;
  float: left;
  font-size: 20px;
  overflow-y: hidden;
}
.lesson-content .item{
  float: left;
  min-height: 50px;
  width: 100%;
  overflow-y: hidden;
}
.item .number{
  padding-left: 10px;
  padding-top: 10px;
}
.item .number-black{
  color: #000;
}
.item .text{
  padding-top: 10px;
}
#grammarResult{
  font-size: 15px;
}

.question-dropdown{
  position: absolute;
  height: 500px;
  width: 50%;
  border: solid 1px #ddd;
  margin-top: 55px;
  right: 0px;
  background: #fff;
}

.question-header{
  height: 20px;
  float: left;
  line-height: 20px;
  width: 100%;
  font-size: 16px !important;
  color: #555 !important;
  text-align: right;
}

.question-header i{
  padding-right: 5px;
}

.question-content{
  width: 100%;
  float: left;
  height: 480px;
  overflow-y: auto;
  text-align: justify;
  padding-left: 10px;
  padding-right: 10px;
  padding-bottom: 10px;
  font-size: 15px !important;
}

.question-active{
  background: #1caceb;
  color: #fff;
}

@media screen and (max-width: 991px) {
  .audio-container{
    width: 100%;
    float: left;
    height: 75px;
    margin-top: 5px;
  }

  .audio-container .test-header, .audio-container .content{
    text-align: left;
  }

  .audio-container-right{
    margin-left: 0%;
  }
  .days-container{
    height: 75px;
    width: 100%;
    float: left;
    margin-top: 5px;
    margin-left: 0% !important;
  }
  .editor-button{
    width: 20%;
    margin-top: 3px;
  }
  .editor-button-char{
    width: 24%;
    margin-top: 3px;
  }
  .test-check-number, .test-check{
    padding-top: 5px;
    padding-left: 0;
    width: 30px;
    height:30px;
    border-radius: 15px;
    color: #fff;
    text-align: center;
    margin-right: 7px;
  }
  .check-button{
    min-height: 100px;
    overflow-y: hidden;
  }

  .check-button span{
    width: 100%;
    float: left;
  }

  .question-dropdown{
    width: 90%;
    right: 5%;
  }
}
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
import {Howl, Howler} from 'howler'
export default {
  mounted(){
    this.getLesson(null)
    this.initTextAreaElement()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      textAreaInput: null,
      lessonId: null,
      lesson: null,
      content: [],
      grammar: null,
      tempContent: [],
      toggleFlag: false,
      flag: false,
      position: null,
      char: [
        {'value': 'Á'},
        {'value': 'á'},
        {'value': 'É'},
        {'value': 'é'},
        {'value': 'Í'},
        {'value': 'í'},
        {'value': 'Ó'},
        {'value': 'ó'},
        {'value': 'Ú'},
        {'value': 'ú'},
        {'value': 'Ñ'},
        {'value': 'ñ'},
        {'value': '¡'},
        {'value': '¿'},
        {'value': 'ª'},
        {'value': 'º'}
      ],
      days: 0,
      remDays: 5,
      textArea: {
        startPos: null,
        endPos: null,
        element: null,
        flag: false,
        insertFlag: false,
        insertPos: null
      },
      obj: null,
      list: [],
      iterator: null,
      grammarQuestionFlag: false
    }
  },
  props: [
    'selectedLesson'
  ],
  watch: {
    selectedLesson(newValue){
      this.content = []
      this.tempContent = []
      this.flag = null
      // this.getLesson(newValue)
    }
  },
  methods: {
    initiateAudioIcon(){
      let iDevices = [
        'iPad Simulator',
        'iPhone Simulator',
        'iPod Simulator',
        'iPad',
        'iPhone',
        'iPod'
      ]
      let flag = false
      if (navigator.platform) {
        while (iDevices.length) {
          if (navigator.platform === iDevices.pop()){
            $('.repeat-audio-icon-ios').css({display: 'none'})
            // console.log('ios')
            flag = true
          }
        }
      }
      if(flag === false){
        // $('.repeat-audio-icon-ios').css({display: 'none'})
        // console.log('other')
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    playHowler(p1, p2){
      this.$parent.audioHandler.playHowler(p1, p2)
    },
    pauseHowler(p1, p2){
      this.$parent.audioHandler.pauseHowler(p1, p2)
    },
    initObj(){
      this.obj = {
        'value': this.textArea.element.value,
        'startPos': this.textArea.element.selectionStart,
        'endPos': this.textArea.element.selectionEnd
      }
    },
    pushToList(){
      this.initObj()
      this.list.push(this.obj)
      this.getCursorPosition()
      if(this.textArea.startPos === null && this.textArea.endPos === null){
        this.iterator = 0
      }else if((this.textArea.startPos - 1) === this.textArea.element.selectionStart || (this.textArea.startPos + 1) === this.textArea.element.selectionStart || this.textArea.startPos === this.textArea.element.selectionStart){
        this.iterator++
      }else{
        this.iterator = this.list.length - 1
      }
    },
    pushToListOnInsert(){
      this.initObj()
      this.list.push(this.obj)
      this.iterator++
    },
    clear(){
      this.initObj()
      this.list.push(this.obj)
      this.iterator = this.list.length - 1
      this.textArea.element.value = ''
      this.initObj()
      this.list.push(this.obj)
      this.iterator = this.list.length - 1
      this.setDisplay(true)
      this.setCursorPosition(1)
    },
    undo(){
      if(this.iterator === null){
        this.iterator = 0
        this.setDisplay(true)
      }else if(this.iterator > 0){
        if(this.textArea.flag === false){
          this.pushToList()
          this.textArea.flag = true
        }else{
          //
        }
        if(this.iterator === 1){
          this.setDisplay(false)
        }else{
          this.iterator--
          this.setDisplay(true)
        }
      }else{
        //
      }
    },
    redo(){
      if(this.iterator === null){
        this.iterator = 1
        this.setDisplay(true)
      }else if(this.iterator < this.list.length){
        if(this.textArea.flag === false){
          this.pushToList()
          this.textArea.flag = true
        }else{
          //
        }
        this.setDisplay(true)
        this.iterator++
      }else if(this.iterator === this.list.length - 1){
        this.setDisplay(true)
      }
    },
    initTextAreaElement(){
      this.textArea.element = document.getElementById('textArea')
      this.setCursorPosition(1)
    },
    setFlag(){
      this.textArea.flag = false
      this.textArea.insertFlag = false
    },
    setDisplay(flag){
      this.textArea.element.focus()
      this.textArea.element.value = this.list[this.iterator].value
      this.setCursorPosition(this.list[this.iterator].endPos)
      this.iterator = (flag === false) ? null : this.iterator
    },
    setCursorPosition(position){
      if(this.textArea.element){
        this.textArea.element.focus()
        this.textArea.element.selectionEnd = position
      }
    },
    getCursorPosition(){
      this.textArea.startPos = this.textArea.element.selectionStart
      this.textArea.endPos = this.textArea.element.selectionEnd
    },
    backspace(event){
      if(event.which === 8 || event.which === 46){
        this.pushToList()
        this.setFlag()
      }
    },
    enter(event){
      if(event.which === 13){
        this.pushToList()
        this.setFlag()
      }
    },
    typing(){
      this.pushToList()
      this.setFlag()
    },
    insert(value){
      if(this.textArea.startPos === null){
        this.textArea.startPos = 1
        this.textArea.endPos = 1
        this.pushToList()
      }else{
        this.textArea.startPos = this.textArea.element.selectionStart
        this.textArea.endPos = this.textArea.element.selectionEnd
      }
      this.textArea.element.blur()
      // this.textArea.element.focus()
      this.textArea.element.value = this.textArea.element.value.substring(0, this.textArea.startPos) + value + this.textArea.element.value.substring(this.textArea.endPos, this.textArea.element.value.length)
      this.textArea.element.selectionStart = this.textArea.startPos + 1
      this.textArea.element.selectionEnd = this.textArea.startPos + 1
      this.pushToListOnInsert()
    },
    getLesson(value){
      this.lessonId = (value === null) ? this.selectedLesson : value
      let parameter = {
        'condition': [{
          'value': this.lessonId,
          'column': 'id',
          'clause': '='
        }]
      }
      this.APIRequest('lesson/retrieve', parameter).then(response => {
        this.lesson = response.data[0]
      }).done(() => {
        this.getTest(this.lessonId)
        this.getContent(this.lessonId)
        this.initiateAudioIcon()
      })
    },
    getTest(lessonId){
      let parameter = {
        'condition': [{
          'value': this.lessonId,
          'column': 'lesson_id',
          'clause': '='
        },
        {
          'value': this.user.userID,
          'column': 'account_id',
          'clause': '='
        }]
      }
      this.APIRequest('test/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          let data = response.data[0]
          this.days = parseInt(data.number_of_days)
          this.remDays = parseInt(5 - this.days)
        }else{
          this.days = 0
          this.remDays = 5
        }
      })
    },
    getContent(lessonId){
      this.remDays = 5 - this.days
      // this.makeActive()
      let parameter = {
        'condition': [{
          'value': this.lessonId,
          'column': 'lesson_id',
          'clause': '='
        }]
      }
      this.APIRequest('content/retrieve', parameter).then(response => {
        this.tempContent = response.data
      })
    },
    activate(type){
      if(type === 'lesson'){
        this.content = this.content.length === 0 ? this.tempContent : []
        this.grammar = null
        // this.flag = true
      }else if(type === 'grammar'){
        this.content = []
        this.grammar = this.lesson.grammar_test_content
        // this.flag = true
      }else{
        this.content = []
        this.grammar = null
        this.flag = false
      }
    },
    toggle(flag){
      this.toggleFlag = flag
      if(flag === true && this.days <= 5){
        this.days += 1
      }else if(flag === false && this.days > 0){
        this.days -= 1
      }else{
        //
      }
      this.remDays = parseInt(5 - this.days)
      this.update()
      // this.makeActive()
    },
    update(){
      let parameter = {
        'lesson_id': this.lessonId,
        'account_id': this.user.userID,
        'number_of_days': this.days
      }
      this.APIRequest('test/update', parameter).then(response => {
        //
      }).done(() => {
        this.$parent.getLesson()
      })
    },
    makeActive(){
      let i, j
      if(this.days === 0){
        for (i = 2; i <= 5; i++) {
          $('.uncheck-button-' + i).css({'background': '#ccc'})
        }
      }else if(this.days < 5){
        for (j = this.days + 2; j <= 5; j++) {
          $('.uncheck-button-' + (j)).css({'background': '#ccc'})
        }
      }
    }
  }
}
</script>
