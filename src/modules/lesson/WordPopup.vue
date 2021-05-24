<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Word Popup Management</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add</button>
      </div>
      <div class="col-lg-10 mx-auto history">
        <div class="filter">
          <label><h5>Filter</h5></label>
          <select class="form-control col-lg-3 col-md-4 col-sm-12" v-model="filterId">
            <option disabled value="">Please Select Word</option>
            <option v-for="item, index in words" class="form-control" v-bind:value="item.id"><label v-html="item.word"></label></option>
          </select>
          <br>
          <button class="btn btn-primary" v-on:click="filter()">Filter</button>
        </div>
        <br>
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Original</td>
              <td>Translation</td>
              <td>Audio URL</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="wordsPopup.length !== 0">
            <tr v-for="item, index in wordsPopup">
              <td>
                <button class="btn btn-primary" v-on:click="play(item.audio)">Play</button>
                <label v-html="item.original"></label></td>
              <td>{{item.translation}}</td>
              <td>{{item.audio}}</td>
              <td><!-- 
                <button class="btn btn-primary" v-on:click="play(item.path + item.audio)">Play</button>
                <button class="btn btn-danger" v-on:click="pause(item.path + item.audio)">Stop</button> -->
                <button class="btn btn-primary" v-on:click="updateModal(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteWord(item.id)"><i class="fa fa-trash"></i>Delete</button>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center" colspan="3">Empty</td>
            </tr>
          </tbody>
        </table>
      </div>

    <!-- Modal 

      EDIT
    -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalWordPopup !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Word Popup</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span v-if="errorMessage !== null" class="text-danger text-center">
                <label><b>Opps! </b>{{errorMessage}}</label>
            </span>
            <br v-if="errorMessage !== null">
            <br v-if="errorMessage !== null">
            <label>Title</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalWordPopup.original">
            <br>
            <label>Translation</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalWordPopup.translation">
            <br>
            <label>Audio URL</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalWordPopup.audio">
            <br> 
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateWordPopup()">update</button>
              <button type="button" class="btn btn-danger"  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>



    <!-- Modal 

      ADD
    -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>{{modalTitle}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span v-if="errorMessage !== null" class="text-danger text-center">
                <label><b>Opps! </b>{{errorMessage}}</label>
            </span>
            <br v-if="errorMessage !== null">
            <br v-if="errorMessage !== null">
            <label>Word</label>
            <br>
            <select class="form-control" v-model="wordId">
              <option v-for="item, index in words" v-bind:value="item.id"><label v-html="item.word"></label></option>
            </select>
            <label>Title</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Original" v-model="original">
            <br>
            <label>Translation</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Translation" v-model="translation">
            <br>
            <label>Audio URL</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Translation" v-model="audio">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()">Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
import {Howl, Howler} from 'howler'
export default {
  mounted(){
    AUTH.redirect()
    this.getWords()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Word Popup',
      errorMessage: null,
      original: null,
      translation: null,
      audio: null,
      words: [],
      wordsPopup: [],
      filterId: null,
      wordId: null,
      modalWordPopup: null,
      modalWordPopupInput: {
        id: null,
        original: null,
        translation: null,
        audio: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    submit(){
      if(this.validation() === true){
        this.errorMessage = null
        this.sendRequest()
      }else{
        this.errorMessage = 'Please fillup the required information'
      }
    },
    sendRequest(){
      let formData = new FormData()
      formData.append('word_audio_id', this.wordId)
      formData.append('original', this.original)
      formData.append('translation', this.translation)
      formData.append('audio', this.audio)
      axios.post(CONFIG.BACKEND_URL + '/word_popup/create', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.result){
          this.getWordsPopup()
        }else{
        }
      })
    },
    filter(id){
      let parameter = {
        'condition': [{
          'column': 'word_audio_id',
          'clause': '=',
          'value': this.filterId
        }]
      }
      this.getWordsPopup(parameter)
    },
    getWordsPopup(parameter){
      this.APIRequest('word_popup/retrieve', parameter).then(response => {
        this.wordsPopup = response.data
      })
    },
    getWords(){
      this.APIRequest('word_audio/retrieve').then(response => {
        this.words = response.data
      })
    },
    deleteWord(index){
      let parameter = {
        id: index
      }
      this.APIRequest('word_popup/delete', parameter).then(response => {
        this.getWordsPopup()
      })
    },
    validation(){
      if(this.original === null || this.translation === null){
        return false
      }else{
        return true
      }
    },
    play(url){
      if(this.audio === null){
        this.audio = new Howl({
          src: CONFIG.BACKEND_URL + url
        })
        this.audio.play()
      }else{
        this.audio.unload()
        this.audio = new Howl({
          src: CONFIG.BACKEND_URL + url
        })
        this.audio.play()
      }
    },
    pause(path){
      this.audio.pause()
    },
    updateModal(index){
      this.modalWordPopup = this.wordsPopup[index]
      this.modalWordPopupInput.id = this.modalWordPopup.id
    },
    updateWordPopup(){
      // let formData = new FormData()
      // formData.append('id', this.modalWordPopupInput.id)
      // if(this.modalWordPopupInput.original !== null){
      //   formData.append('original', this.modalWordPopupInput.original)
      // }
      // if(this.modalWordPopupInput.translation !== null){
      //   formData.append('translation', this.modalWordPopupInput.translation)
      // }
      // if(this.modalWordPopupInput.audio !== null){
      //   formData.append('audio', this.modalWordPopupInput.audio)
      // }else{
      //   //
      // }
      axios.post(CONFIG.BACKEND_URL + '/word_popup/update', this.modalWordPopup).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.getWordsPopup()
        }else{
        }
      })
    }
  }
}
</script>
<style scoped>
.result-holder{
  color: #000;
  margin-bottom: 5%;
  height: 150px;
}

.history{
  margin-top: 25px;
}

.bg-primary{
  background: #1caceb !important; 
}

.modal-title i{
  padding-right: 10px;
}

.form-control{
  height: 45px !important;
}

td button i{
  padding-right: 10px;
}

thead{
  font-weight: 700;
}

.modal-body{
  width: 98% !important;
  margin: 0px 1% 0px 1% !important;
}
</style>
