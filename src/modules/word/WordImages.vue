<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Word Images Management</li>
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
              <td>Type</td>
              <td>Url</td>
              <td>Image</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="wordImages !== null">
            <tr v-for="item, index in wordImages">
              <td>{{item.type}}</td>
              <td>{{item.url}}</td>
              <td class="text-center">
                <img v-bind:src="config.BACKEND_URL + item.url" height="100px" width="auto" v-if="item.type === 'OWN'">
                <img v-bind:src="item.url" height="100px" width="auto" v-else>
              </td>
              <td><!-- 
                <button class="btn btn-primary" v-on:click="play(item.path + item.audio)">Play</button>
                <button class="btn btn-danger" v-on:click="pause(item.path + item.audio)">Stop</button> -->
                <button class="btn btn-primary" v-on:click="setModalWordImages(item)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteItem(item)"><i class="fa fa-trash"></i>Delete</button>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center" colspan="4">Empty</td>
            </tr>
          </tbody>
        </table>
      </div>

    <!-- Modal 

      EDIT
    -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalWordImage !== null">
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
            <label>Word</label>
            <br>
            <select class="form-control" v-model="modalWordImage.word_audio_id">
              <option v-for="item, index in words" v-bind:value="item.id"><label v-html="item.word"></label></option>
            </select>
            <label>Type</label>
            <br>
            <select class="form-control" v-model="modalWordImage.type">
              <option value="OWN">TALKFLUENT</option>
              <option value="THIRD_PARTY">THIRD PARTY</option>
            </select>
            <br>
            <label>Url</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Url" v-model="modalWordImage.url">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="update()">Update</button>
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
            <select class="form-control" v-model="newWordImage.word_audio_id">
              <option v-for="item, index in words" v-bind:value="item.id"><label v-html="item.word"></label></option>
            </select>
            <label>Type</label>
            <br>
            <select class="form-control" v-model="newWordImage.type">
              <option value="OWN">TALKFLUENT</option>
              <option value="THIRD_PARTY">THIRD PARTY</option>
            </select>
            <br>
            <label>Url</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Url" v-model="newWordImage.url">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()">Submit</button>
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
      modalTitle: 'Add Word Image',
      errorMessage: null,
      words: [],
      filterId: null,
      wordId: null,
      newWordImage: {
        word_audio_id: null,
        type: null,
        url: null
      },
      wordImages: null,
      config: CONFIG,
      modalWordImage: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    filter(){
      this.retrieve(this.filterId)
    },
    getWords(){
      this.APIRequest('word_audio/retrieve').then(response => {
        this.words = response.data
      })
    },
    submit(){
      if(this.validateNewInput(this.newWordImage) === true){
        this.create()
      }
    },
    validateNewInput(t){
      if(t.word_audio_id === null || t.word_audio_id === '' || t.type === null || t.type === '' || t.url === null || t.url === ''){
        this.errorMessage = 'Please fill up the required informations'
        return false
      }else{
        this.errorMessage = null
        return true
      }
    },
    create(){
      this.APIRequest('word_images/create', this.newWordImage).then(response => {
        if(response.data > 0){
          this.retrieve(this.newWordImage.word_audio_id)
          $('#myModal').modal('hide')
          this.clear()
        }
      })
    },
    retrieve(wordAudioId){
      let parameter = {
        'condition': [{
          'column': 'word_audio_id',
          'clause': '=',
          'value': wordAudioId
        }]
      }
      this.APIRequest('word_images/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.wordImages = response.data
        }else{
          this.wordImages = null
        }
      })
    },
    setModalWordImages(item){
      this.modalWordImage = item
    },
    update(){
      if(this.validateNewInput(this.modalWordImage) === true){
        this.APIRequest('word_images/update', this.modalWordImage).then(response => {
          if(response.data === true){
            this.wordImages = response.data
            this.retrieve(this.modalWordImage.word_audio_id)
            $('#editModal').modal('hide')
          }
        })
      }
    },
    deleteItem(item){
      let parameter = {
        id: item.id
      }
      this.APIRequest('word_images/delete', parameter).then(response => {
        this.retrieve(item.word_audio_id)
      })
    },
    clear(){
      this.newWordImage.word_audio_id = null
      this.newWordImage.type = null
      this.newWordImage.url = null
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
