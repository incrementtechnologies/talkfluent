<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" v-on:click="loadLessons()"><i class="fa fa-plus"></i> Add Answer</button>
      </div>
      <div class="col-lg-10 mx-auto history">
        <div class="filter">
          <label><h5>Select Lesson</h5></label>
          <div class="form-inline">
            <select class="form-control col-lg-3 col-md-4" v-model="filterLessonId" @change="filterContent()">
              <option v-for="item, index in lesson" class="form-control" v-bind:value="item.id">{{item.title}}</option>
            </select>
            <select class="form-control col-lg-3 col-md-4 mx-lg-5" v-model="filterContentId">
              <option v-for="item, index in content" class="form-control" v-bind:value="item.id">{{item.original}}</option>
            </select>
          </div>
          <br>
          <button class="btn btn-primary" v-on:click="filter()">Filter</button>
        </div>
        <br>
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Original</td>
              <td>Translation</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="answer.length !== 0">
            <tr v-for="item, index in answer">
              <td>{{item.original}}</td>
              <td>{{item.translation}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="play(item.path + item.audio)">Play</button>
                <button class="btn btn-danger" v-on:click="pause(item.path + item.audio)">Stop</button>
                <button class="btn btn-primary" v-on:click="modalUpdate(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteAnswer(item.id)"><i class="fa fa-trash"></i>Delete</button>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="updateModalAnswer !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Answer</h5>
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
            <br> 
            <label>Title</label>
            <br>
            <input type="text" name="title" class="form-control" v-bind:placeholder="updateModalAnswer.original" v-model="updateModalAnswerInput.original">
            <br>
            <label>Translation</label>
            <br>
            <input type="text" name="title" class="form-control" v-bind:placeholder="updateModalAnswer.translation" v-model="updateModalAnswerInput.translation">
            <br> 
            <label>Sentence Audio</label>
            <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileUpload($event)">  
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateAnswer()">Update</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
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
            <label>Lesson</label>
            <br>
            <select class="form-control" v-model="modalLessonId" @change="modalFilterLesson()">
              <option v-for="item, index in modalLesson" v-bind:value="item.id">{{item.title}}</option>
            </select>
            <br>
            <div v-if="modalContent.length !== 0">
              <label>Content</label>
              <br>
              <select class="form-control" v-model="modalContentId">
                <option v-for="item, index in modalContent" v-bind:value="item.id">{{item.original}}</option>
              </select>
            </div>
            <div v-if="modalContent.length !== 0">
              <label>Title</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Original" v-model="original">
              <br>
              <label>Translation</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Translation" v-model="translation">
              <br> 
              <label>Sentence Audio</label>
              <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileUpload($event)">  
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="modalContent.length !== 0">Submit</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
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
export default {
  mounted(){
    AUTH.redirect()
    this.getLesson()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Content',
      content: [],
      errorMessage: null,
      closeFag: false,
      original: null,
      translation: null,
      audio: null,
      file: null,
      lesson: [],
      filterLessonId: null,
      filterContentId: null,
      modalLessonId: null,
      answer: [],
      modalContentId: null,
      modalLesson: [],
      modalContent: [],
      updateModalAnswer: null,
      updateModalAnswerInput: {
        id: null,
        original: null,
        translation: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    setUpFileUpload(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.file = files[0]
        this.createAudio(files[0])
      }
    },
    createAudio(file){
      let fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
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
      formData.append('file', this.file)
      formData.append('filename', this.file.name)
      formData.append('content_id', this.modalContentId)
      formData.append('original', this.original)
      formData.append('translation', this.translation)
      axios.post(CONFIG.BACKEND_URL + '/answers/create', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.result){
          this.closeFag = true
          ROUTER.go('/lesson_management')
        }else{
          this.closeFag = false
        }
      })
    },
    filterContent(){
      let parameter = {
        'condition': [{
          'column': 'lesson_id',
          'clause': '=',
          'value': this.filterLessonId
        }]
      }
      this.getContent(parameter)
    },
    filter(id){
      let parameter = {
        'condition': [{
          'column': 'content_id',
          'clause': '=',
          'value': this.filterContentId
        }]
      }
      this.getAnswer(parameter)
    },
    getAnswer(parameter){
      this.APIRequest('answers/retrieve', parameter).then(response => {
        this.answer = response.data
      })
    },
    getContent(parameter){
      this.APIRequest('contents/retrieve', parameter).then(response => {
        this.content = response.data
      })
    },
    getLesson(){
      this.APIRequest('lesson/retrieve').then(response => {
        this.lesson = response.data
      })
    },
    deleteAnswer(index){
      let parameter = {
        id: index
      }
      this.APIRequest('answers/delete', parameter).then(response => {
        ROUTER.go('/answer_management')
      })
    },
    validation(){
      if(this.original === null || this.translation === null){
        return false
      }else{
        return true
      }
    },
    play(path){
      this.audio = new Audio(CONFIG.BACKEND_URL + path)
      this.audio.play()
    },
    pause(path){
      this.audio.pause()
    },
    loadLessons(){
      this.APIRequest('lesson/retrieve').then(response => {
        this.modalLesson = response.data
      })
    },
    modalFilterLesson(){
      let parameter = {
        'condition': [{
          'column': 'lesson_id',
          'clause': '=',
          'value': this.modalLessonId
        }]
      }
      this.APIRequest('contents/retrieve', parameter).then(response => {
        this.modalContent = response.data
      })
    },
    modalUpdate(index){
      this.updateModalAnswer = this.answer[index]
      this.updateModalAnswerInput.id = this.updateModalAnswer.id
    },
    updateAnswer(){
      let formData = new FormData()
      formData.append('id', this.updateModalAnswerInput.id)
      if(this.file !== null){
        formData.append('file', this.file)
        formData.append('filename', this.file.name)
      }
      if(this.updateModalAnswerInput.original !== null){
        formData.append('original', this.updateModalAnswerInput.original)
      }
      if(this.updateModalAnswerInput.translation !== null){
        formData.append('translation', this.updateModalAnswerInput.translation)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/answers/update', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data){
          this.closeFag = true
          ROUTER.go('/lesson_management')
        }else{
          this.closeFag = false
        }
      })
    }
  }
}
</script>
<style>
.result-holder{
  color: #000;
  margin-bottom: 5%;
  height: 150px;
}
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
  height: 120px;
  border: 4px dashed;
  border-radius: 3px;
}
form p{
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 120px;
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
</style>
