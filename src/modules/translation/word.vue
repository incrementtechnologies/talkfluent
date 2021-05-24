<template>
<div class="row">
  <div class="common-holder" v-bind:class="{'col-lg-8': pop}">
    <h5 class="to-right"><i class="fa fa-play play-small"></i><b class="to-right">{{title}}</b></h5>
    <div class="result-holder" v-bind:class="{'col-8':pop}">
      <div class="row to-right">
        <span class="tooltips" v-on:click="showPopUp(), answerRetrieve(act+1), setSelect(act+1), clicked = 1, popsentence = true" v-bind:class="{'tooltips-clicked': clicked === 1}"><b>{{act+1}}.&nbsp;</b></span>
        <span v-for="item, index in les" class="tooltips" v-on:click="showPopUp(item), selected = item.word, item.click = 1, popsentence = false" v-on:mouseover="wordRetrieve(item.word)" v-bind:class="{'tooltips-clicked': item.click === 1}">
          <b>{{item.word}} &nbsp;</b>
          <span class="tooltiptext" v-if="wordAudio !== null">{{wordAudio.translation}}</span>
          <span class="tooltiptext" v-else>Not Found</span>
        </span>
        <br>
        <br>
        <span v-for="item, index in less" v-if="act === index" class="translation" >{{index+1}}. {{item.translation}}</span>
      </div>
    </div>
    <div v-if="!pop">
      <div class="tutorial-area">
        <h5>
          <i class="fa fa-play play-small"></i><b class="to-right">Grammar and Culture</b>
        </h5>
        <p class="to-right">{{gtip}}<br><span>{{ctip}}</span></p>
      </div>
    </div>
    <div class="row" v-else>
      <div class="tutorial-area col-4">
        <h5>
          <i class="fa fa-play play-small"></i><b> Grammar </b>
        </h5>
        <p class="to-right">{{gtip}}</p>
      </div>
      <div class="tutorial-area col-4">
        <h5>
          <i class="fa fa-play play-small"></i><b> Culture</b>
        </h5>
        <p class="to-right">{{ctip}}</p>
      </div>
    </div>
  </div>
  <div class="pop-up col-4 col-xs-12" v-bind:class="{popupvisible: pop}">
    <div class="pop-up-header col-1">
      <button type="button" class="close" aria-label="Close" v-on:click ="closePopUp(), wavesurfer.pause()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="pop-up-header">
      <button type="button" class="fa fa-volume-up volume-up to-right" v-on:click="play(wordAudio.audio)"></button><b class="to-right">{{selected}}</b><br/>
      <span class="to-right">{{selectedTrans}}</span>
    </div>
    <div id="waveform" class="to-right"></div>
    <div class="audioSetting div-margin-top-btm">
      <!-- <button class="btn btn-primary circle-btn">
        <i class="fa fa-volume-down"></i>
      </button>
      <button class="btn btn-primary circle-btn" v-on:click="play(), icon = !icon">
        <i v-bind:class="changeIcon()" ></i>
      </button>
      <button class="btn btn-primary circle-btn">
        <i class="fa fa-volume-up"></i>
      </button> -->
    </div>
    <div class="div-margin-top-btm">
      <span aria-hidden="true" class="fa fa-volume-up volume-up to-right"></span><b class="to-right">{{selected}}</b>
    </div>
    <div class="to-right">
      <textarea cols="30" rows="10" readonly>               Stress and Accents

    {{acc}}
      </textarea>
      <div v-if="popsentence">
        <div v-for="item, index in answer">
          <span>1. {{selected}}</span><span>1. translation</span>
          <span class="to-right">2. {{item.original}}</span>
          <span>2. {{item.translation}}</span>
        </div>  
      </div>
      <div v-for="item, index in less" v-on:click="active = index, splitSentence(item.original, index), act = active, les = sentence, selected= null, pop = false, clicked = 0" v-else>
        <span>{{index+1}}. {{item.original}}</span>
        <span>{{index+1}}. {{item.translation}}</span>
      </div>   
    </div>
  </div>
</div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import WaveSurfer from 'wavesurfer.js'
export default {
  mounted(){
    AUTH.redirect()
    this.init()
  },
  props: ['lessonN', 'les', 'title', 'acc', 'pop', 'ctip', 'gtip', 'clicked', 'less', 'act'],
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      objSent: [[{word: null}]],
      translated: ['hi', 'how', 'these'],
      tranSentence: null,
      sentence: [{}],
      selected: null,
      selectedTrans: null,
      wavesurfer: '',
      iconPlay: 'fa fa-play',
      iconPause: 'fa fa-pause',
      icon: 0,
      lessonNo: null,
      lesson: [],
      minimap: null,
      sentences: null,
      counter: 0,
      active: 0,
      answer: [],
      activeSent: null,
      popsentence: false,
      wordAudio: null
    }
  },
  methods: {
    setSelect(id){
      this.selected = this.less[id - 1].original
    },
    splitSentence(spltStr, index){
      this.sentence = []
      var text = spltStr.split(' ')
      var len = text.length
      while(this.sentence.length !== 0){
        this.sentence.pop()
      }
      this.active = 0

      for (var i = 0; i < len; i = i + 1) {
        this.sentence.push({
          word: text[i],
          click: 0
        })
      }
      this.active = index
    },
    init(){
      this.wavesurfer = WaveSurfer.create({
        container: '#waveform',
        waveColor: '#555',
        height: '50',
        progressColor: '#1caceb',
        barWidth: 2
      })
    },
    loadAudio(file){
      this.wavesurfer.load(CONFIG.BACKEND_URL + '/storage/word/' + file)
    },
    play() {
      this.wavesurfer.playPause()
    },
    showPopUp(){
      this.pop = true
    },
    closePopUp(){
      this.pop = false
    },
    changeIcon(){
      if(!this.icon){
        return this.iconPlay
      } else {
        return this.iconPause
      }
    },
    displaySent(){
      var max = 0
      for (var p in this.sentenceDB){
        if (this.sentenceDB.hasOwnProperty(p)){
          max++
        }
      }
      for(var i = 0; i < this.size; i++){
        for(var j = 0; j < max; j++){
          if(this.sentenceDB.id === i){
            this.objSent.word.push({
              word: this.sentenceDB.word
            })
          }
        }
      }
    },
    checkPrev(id){
      if (this.sentenceDB[id].id === this.sentenceDB[id - 1].word){
        return true
      } else {
        return false
      }
    },
    answerRetrieve(act){
      let parameter = {
        'condition': [{
          'column': 'content_id',
          'clause': '=',
          'value': act
        }]
      }
      this.APIRequest('answers/retrieve', parameter).then(response => {
        this.answer = response.data
        console.log(this.answer)
      })
    },
    wordRetrieve(word){
      let parameter = {
        'condition': [{
          'column': 'word',
          'clause': '=',
          'value': word
        }]
      }
      console.log(parameter)
      this.APIRequest('word_audio/retrieve', parameter).then(response => {
        if(response.data.length !== 0){
          this.wordAudio = response.data[0]
        }else{
          this.wordAudio = null
        }
      })
    }
  }
}
</script>
<style>
#waveform{
  /*visibility: hidden;*/
  /*display: none;*/
}

.translation{
  color: gray;
  margin-top: 5px;
  font-size: 14px;
}
.no-display{
  display: none;
}
.translation-area{
  color: #e1e1e1;
}
.div-margin-top-btm{
  margin: 10px 0 10px 0;
}
.circle-btn{
  border-radius: 50%;
}
.audioSetting{
  text-align: center;
}
.to-right{
  margin-left: 20px;
}
.tutorial-area{
  height: 200px;
  background: #efefef;
  margin: 0 0px 0 50px;
}
/* Tooltip container */
.tooltips {
    position: relative;
    display: inline-block;
    color: gray;
}

.tooltips-new-line {
    position: relative;
    color: gray;
}
.tooltips-clicked{
    color: #000 !important;
}

/* Tooltip text */
.tooltips .tooltiptext,  .tooltips-new-line .tooltiptext{
    visibility: hidden;
    width: 120px;
    background-color: #e1e1e1;
    color: black;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 1;
    white-space: pre-line;
}

/* Tooltip arrow */
.tooltips .tooltiptext::after, .tooltips-new-line .tooltiptext::after{
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #e1e1e1 transparent transparent transparent;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltips:hover .tooltiptext, .tooltips-new-line:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

.common-holder{
  width: 95%;
}

.header-holder{
  font-size: 28px;
  margin-left: 30px;
  width: 75%;
}
.pop-up-holder{
  width: 25%;
}

.result-holder{
  font-weight: 400;
  color: #000;
  margin-left: 5%;
  font-size: 20px;
  height: 300px;
  margin-top: 20px;
}
.play{
  width: 75px;
  height: 75px;
  line-height: 75px;
}

.play-small{
  width: 45px;
  height: 45px;
  line-height: 45px;
}

.play, .play-small{
  margin-top: 20px;
  color: white;
  background-color: #1caceb;
  border-radius: 50%; /* the magic */
  text-align: center;
}
#left, #right {
    width: 50%;
    height: 100%;
    position: absolute;
    z-index: -1;
}

.pop-up{
  min-height: 100%;
  background: black;
  background: #efefef;
  display: none;
}
.popupvisible{
  display: block;
}

.pop-up-header{
  margin-top: 10px;
  font-size: 20px;
}

.hideOnPop{
  display: none;
}
.volume-up{
  font-size: 25px;
  color:  #1caceb;
  background: white;
  border: solid 2px #1caceb;
  border-radius: 50%;
  width: 45px;
  height: 45px;
  line-height: 45px;
  text-align: center;
}
</style>
