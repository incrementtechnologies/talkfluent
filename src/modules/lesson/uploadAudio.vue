<template>
  <div id="container">
    <div id="left"></div>
    <div id="right"></div>
    <div id="content">
      <div class="common-holder">
        <div class="header-holder">
          <i class="fa fa-play play"></i><b class="to-right"> Upload Audio Tutorials</b>
        </div>
        <div class="result-holder">
          <form>
            <div class="input-area">
              <input type="file" multiple name="audio" accept="audio/*" @change="fileCount = $event.target.files.length, add($event.target.files)" class="btn">
              <p v-if="fileCount === 0"> <i class="fa fa-upload"></i> Drag and Drop Audio Files</p>
              <p v-else>{{fileCount}} audio<mark v-if="fileCount===1">file</mark><mark v-else>files</mark>to be uploaded</p>
            </div>
            <div class="btn-area">
              <button class="btn-primary btn">Upload</button>
            </div> 
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'

export default {
  mounted(){
    AUTH.redirect()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      audio: [],
      fileCount: 0
    }
  },
  methods: {
    add(e){
      console.log(e)
    }
  }
}
</script>
<style>
mark{
  background: none;
}
form{
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -100px;
  margin-left: -250px;
  width: 500px;
  height: 180px;
  border: 4px dashed;
  border-radius: 3px;
}
form p{
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 170px;
}
form input{
  position: absolute;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  outline: none;
  opacity: 0;
}
.input-area:hover{
  background-color: #e5f8ff;
}
form button{
  text-align: center;
}
button:hover{
  background-color: #e5f8ff !important;
  color: black !important;
  border-color: black !important;
}
.btn-area{
  text-align: center;
}
.to-right{
  margin-left: 20px;
}

.common-holder{
  width: 100%;
}

.margin-color{
  width:100%;
  background-color: #2d2e30 !important;
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
  margin: 3% 6% 0 6%;
  font-size: 20px;
  height: 500px;
}

.header-holder{
  font-size: 28px;
  margin-left: 30px;
  width: 75%;
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

#left {
    left: 0;
    background-color: #2d2e30;
}

#right {
    right: 0;
    background-color: #2d2e30;
}
#container {
    width: 100%;
    min-height: 100%;
    position: relative;
}
#content {
    width: 75%;
    min-height: 100%;
    margin: 0 auto;
    background-color: white;
}
</style>
