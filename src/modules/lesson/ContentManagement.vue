<template>
  <div class="row">
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
              <td>{{item.original}}</td>
              <td>{{item.translation}}</td>
              <td>{{item.syllabus}}</td>
              <td><!-- 
                <button class="btn btn-primary" v-on:click="play(item.path + item.audio)">Play</button>
                <button class="btn btn-danger" v-on:click="pause(item.path + item.audio)">Stop</button> -->
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
            <input type="text" name="title" class="form-control" v-bind:placeholder="modalContent.original" v-model="modalContentInput.original">
            <br>
            <label>Translation</label>
            <br>
            <input type="text" name="title" class="form-control" v-bind:placeholder="modalContent.translation" v-model="modalContentInput.translation">
            <br> 
            <label>Syllabus</label>
            <br>
            <input type="text" name="title" class="form-control" v-bind:placeholder="modalContent.syllabus" v-model="modalContentInput.syllabus">
            <br> 
            <label>Syllabus Audio</label>
            <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpSyllabusFileUpload($event)">
            <br> 
            <label>Sentence Audio</label>
            <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileUpload($event)">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateContent()" v-if="closeFag == false">update</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
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
            <label>Syllabus</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Syllabus" v-model="syllabus">
            <br> 
            <label>Syllabus Audio</label>
            <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpSyllabusFileUpload($event)">
            <br> 
            <label>Sentence Audio</label>
            <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileUpload($event)">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Submit</button>
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
      syllabus: null,
      audio: null,
      file: null,
      syllabusFile: null,
      lesson: [],
      filterId: null,
      lessonId: null,
      modalContent: null,
      modalContentInput: {
        id: null,
        original: null,
        translation: null,
        syllabus: null
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
      formData.append('file', this.file)
      formData.append('filename', this.file.name)
      formData.append('lesson_id', this.lessonId)
      formData.append('original', this.original)
      formData.append('translation', this.translation)
      formData.append('syllabus', this.syllabus)
      formData.append('syllabus_filename', this.syllabusFile.name)
      formData.append('syllabus_file', this.syllabusFile)
      axios.post(CONFIG.BACKEND_URL + '/contents/create', formData).then(response => {
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
    filter(id){
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
      this.APIRequest('contents/retrieve', parameter).then(response => {
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
      this.APIRequest('contents/delete', parameter).then(response => {
        ROUTER.go('/content_management')
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
    updateModal(index){
      this.modalContent = this.content[index]
      this.modalContentInput.id = this.modalContent.id
    },
    updateContent(){
      let formData = new FormData()
      formData.append('id', this.modalContentInput.id)
      if(this.modalContentInput.original !== null){
        formData.append('original', this.modalContentInput.original)
      }
      if(this.modalContentInput.translation !== null){
        formData.append('translation', this.modalContentInput.translation)
      }
      if(this.modalContentInput.syllabus !== null){
        formData.append('syllabus', this.modalContentInput.syllabus)
      }
      if(this.file !== null){
        formData.append('file', this.file)
        formData.append('filename', this.file.name)
      }
      if(this.syllabusFile !== null){
        formData.append('syllabus_file', this.syllabusFile)
        formData.append('syllabus_filename', this.syllabusFile.name)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/contents/update', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
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
