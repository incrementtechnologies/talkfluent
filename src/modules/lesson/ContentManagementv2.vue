<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Content Management</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Content</button>
      </div>
      <div class="col-lg-10 mx-auto history">
        <div class="filter">
          <label><h5>Filter</h5></label>
          <select class="form-control col-lg-3 col-md-4 col-sm-12" v-model="filterId">
            <option disabled value="">Please Select Lesson</option>
            <option v-for="item, index in lesson" class="form-control" v-bind:value="item.id">{{item.title}}</option>
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
              <td>Syllabus</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="content.length !== 0">
            <tr v-for="item, index in content">
              <td>
                <button class="btn btn-primary" v-on:click="play(item.audio)">Play</button>
                <label v-html="item.original"></label>
              </td>
              <td>{{item.translation}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="play(item.syllabus_audio)">Play</button>
                <label v-html="item.syllabus"></label>
              </td>
              <td>
                <button class="btn btn-primary" v-on:click="updateModal(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteContent(item.id)"><i class="fa fa-trash"></i>Delete</button>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalContent !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Content</h5>
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
            <input type="text" name="title" class="form-control" v-model="modalContent.original">
            <br>
            <label>Translation</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalContent.translation">
            <br> 
            <label>Audio</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalContent.audio">
            <br> 
            <label>Syllabus</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalContent.syllabus">
            <br> 
            <label>Syllabus Audio</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalContent.syllabus_audio">
            <br>
            <label>Video Url</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalContent.video_url">
            <br>
            <label>Accent Text (Optional)</label>
            <br>
            <textarea class="form-control" rows="10" v-model="modalContent.accent_text"></textarea>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateContent()">update</button>
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
            <select class="form-control" v-model="lessonId">
              <option v-for="item, index in lesson" v-bind:value="item.id">{{item.title}}</option>
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
            <input type="text" name="title" class="form-control" placeholder="Audio URL" v-model="audio">
            <br>
            <label>Syllabus</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Syllabus" v-model="syllabus">
            <br>
            <label>Video Url</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="videoUrl" v-model="videoUrl">
            <br> 
            <label>Syllabus Audio URL</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Audio URL" v-model="syllabusAudio">
            <br>
            <label>Accent Text (Optional)</label>
            <br>
            <textarea class="form-control" rows="10" v-model="accentText"></textarea>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()">Submit</button>
              <button type="button" class="btn btn-danger"  data-dismiss="modal" aria-label="Close">Close</button>
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
    this.getLesson()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Content',
      content: [],
      errorMessage: null,
      original: null,
      translation: null,
      audio: null,
      syllabus: null,
      accentText: null,
      syllabusAudio: null,
      file: null,
      syllabusFile: null,
      videoUrl: null,
      lesson: [],
      filterId: null,
      lessonId: null,
      modalContent: null,
      modalContentInput: {
        id: null,
        original: null,
        translation: null,
        audio: null,
        syllabus: null,
        syllabusAudio: null,
        videoUrl: null
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
    setUpSyllabusFileUpload(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.syllabusFile = files[0]
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
      formData.append('lesson_id', this.lessonId)
      formData.append('original', this.original)
      formData.append('translation', this.translation)
      formData.append('audio', this.audio)
      formData.append('syllabus', this.syllabus)
      formData.append('syllabus_audio', this.syllabusAudio)
      formData.append('accent_text', this.accentText)
      formData.append('video_url', this.videoUrl)
      axios.post(CONFIG.BACKEND_URL + '/content/create', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.result){
          this.filter()
        }else{
        }
      })
    },
    filter(){
      let parameter = {
        'condition': [{
          'column': 'lesson_id',
          'clause': '=',
          'value': this.filterId
        }]
      }
      this.getContent(parameter)
    },
    getContent(parameter){
      this.APIRequest('content/retrieve', parameter).then(response => {
        this.content = response.data
      })
    },
    getLesson(){
      this.APIRequest('lesson/retrieve').then(response => {
        this.lesson = response.data
      })
    },
    deleteContent(index){
      let parameter = {
        id: index
      }
      this.APIRequest('content/delete', parameter).then(response => {
        this.filter()
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
      this.modalContent = this.content[index]
      this.modalContentInput.id = this.modalContent.id
    },
    updateContent(){
      axios.post(CONFIG.BACKEND_URL + '/content/update', this.modalContent).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.filter()
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
