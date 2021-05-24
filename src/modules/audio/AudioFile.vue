<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Audio File Management</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add</button>
      </div>
      <div class="col-lg-10 mx-auto history">
        <br>
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>URL</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="audioFiles.length !== 0">
            <tr v-for="item, index in audioFiles">
              <td>{{item.url + item.filename}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="play(item.url + item.filename)">Play</button>
                <button class="btn btn-primary" v-on:click="updateModal(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteAccent(item.id)"><i class="fa fa-trash"></i>Delete</button>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalFile !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update File</h5>
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
            <label>File</label>
            <br>
            <input type="file" name="title" class="form-control" accept="audio/*"  @change="setUpFileUpload($event)" placeholder="Audio File">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateAccent()" v-if="closeFag == false">update</button>
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
            <br>
            <label>File</label>
            <br>
            <input type="file" name="title" class="form-control" accept="audio/*"  @change="setUpFileUpload($event)" placeholder="Audio File">
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
    this.getAudio()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Audio File',
      audioFiles: [],
      errorMessage: null,
      closeFag: false,
      file: null,
      title: null,
      modalFile: null,
      modalFileInput: {
        id: null,
        title: null
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
      formData.append('audio_file', this.file)
      formData.append('filename', this.file.name)
      axios.post(CONFIG.BACKEND_URL + '/audio_file/create', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.result){
          this.closeFag = true
          ROUTER.go('/audio_files')
        }else{
          this.closeFag = false
        }
      })
    },
    getAudio(){
      this.APIRequest('audio_file/retrieve').then(response => {
        this.audioFiles = response.data
      })
    },
    deleteAccent(index){
      let parameter = {
        id: index
      }
      this.APIRequest('audio_file/delete', parameter).then(response => {
        ROUTER.go('/audio_files')
      })
    },
    validation(){
      if(this.url === null){
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
      this.modalFile = this.audioFiles[index]
      this.modalFileInput.id = this.modalFile.id
    },
    updateAccent(){
      let formData = new FormData()
      formData.append('id', this.modalFileInput.id)
      if(this.file !== null){
        formData.append('filename', this.file.name)
        formData.append('audio_file', this.file)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/audio_file/update', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.closeFag = true
          ROUTER.go('/audio_files')
        }else{
          this.closeFag = false
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
