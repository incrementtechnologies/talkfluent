<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Word Management</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> New Word</button>
      </div>

      <div class="col-lg-10 mx-auto">
        <ul class="pagination">
          <li class="page-item" v-for="item, index in alphabet" @click="retrieve(item)">
            <span class="page-link">{{item.title}}</span>
          </li>
        </ul>
      </div>

      <div class="col-lg-10 mx-auto history">
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Word</td>
              <td>Translation</td>
              <td>Syllabus</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="audioList !== null">
            <tr v-for="item, index in audioList">
              <td>
                <button class="btn btn-primary" v-on:click="play(item.audio)">Play</button>
                <label v-html="item.word"></label>
              </td>              
              <td>{{item.translation}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="play(item.syllabus_audio)">Play</button>
                <label v-html="item.syllabus"></label>
              </td>
              <td>
                <div class="accent-holder" v-bind:id="'ipa-' + index" v-if="item.accent_text !== null && prevIndex === index">
                  <span class="header">
                    <i class="fa fa-close pull-right" @click="hideIPA(index)"></i>
                  </span>
                  <span v-html="item.accent_text"></span>
                </div>
                <button class="btn btn-warning" @click="showIPA(index)" v-if="item.accent_text !== null">IPA</button>
                <button class="btn btn-primary" v-on:click="editModal(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
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


          <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalAudio !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Word Translation</h5>
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
              <label>Spanish Word</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.word">
              <br>
              <label>Plain Spanish Word</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.plain">
              <br>
              <label>Translation</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.translation">
              <br>
              <label>Word Audio</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.audio">
              <br>
              <label>Syllabus</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.syllabus">
              <br>
              <label>Syllabus Audio</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.syllabus_audio">
              <br>
              <label>Accent Text(Optional)</label>
              <br>
              <textarea class="form-control" v-model="modalAudio.accent_text" placeholder="Optional"></textarea>
              <br>
              <label>Transcription(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.transcription">
              <br>
              <label>Classifications(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.classifications">
              <br>
              <label>Pronounciation(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.pronounciation">
              <br>
              <label>Video Url(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="modalAudio.video_url">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateWord()">Update</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
      </div>
    </div>



    <!-- Modal -->
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
              <label>Spanish Word</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Spanish Word" v-model="word">
              <br>
              <label>Plain Spanish Word</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Translation" v-model="plain">
              <br>
              <label>Translation</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Translation" v-model="translation">
              <br>
              <label>Word Audio</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Word Audio" v-model="audio">
              <br>
              <label>Syllabus</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Syllabus" v-model="syllabus">
              <br>
              <label>Syllabus Audio</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Syllabus Audio" v-model="syllabusAudio">
              <br>
              <label>Accent Text(Optional)</label>
              <br>
              <textarea class="form-control" v-model="accentText" placeholder="Optional"></textarea>
              <br>
              <label>Transcription(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="transcription">
              <br>
              <label>Classifications(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="classifications">
              <br>
              <label>Pronounciation(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="pronounciation">
              <br>
              <label>Video Url(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" v-model="videoUrl">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()">Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>
<style scoped>
.result-holder{
  color: #000;
  margin-bottom: 5%;
  height: 150px;
}

.bg-primary{
  background: #1caceb !important; 
}

.modal-title i{
  padding-right: 10px;
}

.modal-body{
  width: 98% !important;
  margin: 0px 1% 0px 1% !important;
}

.page-item:hover{
  cursor: pointer;
}
.accent-holder{
  position: absolute;
  z-index: 5;
  height: 400px;
  width: 500px;
  margin-left: -500px;
  background: #fff;
  border: solid 1px #ddd;
  display: none;
}

.accent-holder .header{
  width: 20px;
  line-height: 40px;
  width: 100%;
  color: #000;
}
.header i{
  padding-right: 20px;
  line-height: 40px;
  font-size: 20px;
}

.header i:hover{
  cursor: pointer;
}
.accent-header-holder{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}
.accent-header{
  width: 98%;
  float: left;
  min-height: 30px;
  font-weight: 600;
  margin: 0 1% 0 1%;
  z-index: 10000;
  font-size: 28px !important;
  overflow-y: hidden;
}
.accent-header label{
  color: #1caceb;
}
.accent-header label:hover{
  cursor: pointer;
  text-decoration: underline;
}
.accent-item-holder{
  height: 300px;
  width: 100%;
  overflow-y: scroll;
  float: left;
}
.accent-header-extra{
  width: 98%;
  float: left;
  height: 30px;
  font-weight: 600;
  margin: 0 1% 0 1%;
  font-size: 20px !important;
}

.accent-item{
  height: auto;
  width: 96%;
  margin: 10px 2% 10px 2%;
  float: left;
}
.accent-content{
  width: 100%;
  float: left;
  height: auto;
}
.accent-content-left{
  width: 22%;
  float: left;
}
.accent-audio{
  width: 50%;
  float: left;
}
.accent-letter{
  width: 50%;
  float: left;
  margin-top: -15px;
  text-align: center;
}
.accent-letter label{
  width: 100%;
  float: left;
  font-size: 50px;
}
.accent-letter .bottom{
  font-size: 15px;
  margin-top: -20px;
  color: #555;
}
.accent-letter-defi{
  width: 72%;
  margin-left: 6%;
  float: left;
  text-align: justify;
}

.accent-letter-defi ul{
  float: left;
}

.accent-letter-defi-extra{
  width: 88%;
  float: left;
  text-align: justify;
}

.accent-media-holder{
  width: 100%;
  float: left;
  text-align: center;
}
.accent-media-holder-extra{
  width: 100%;
  float: left;
  text-align: center;
}
.accent-media-item{
  float: left;
  height: 45px;
  width: 100%;
  margin-top: 5px;
}
.accent-media-item:hover{
  cursor: pointer;
  border: 1px solid #ccc;
}
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    AUTH.redirect()
    this.retrieve(this.alphabet[0])
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Word Audio',
      audioList: [],
      audioFile: '',
      syllabusFile: null,
      translation: null,
      audio: null,
      accentText: null,
      transcription: null,
      classifications: null,
      syllabus: null,
      syllabusAudio: null,
      pronounciation: null,
      word: null,
      plain: null,
      closeFag: false,
      audioPlay: null,
      errorMessage: null,
      file: null,
      modalAudio: null,
      videoUrl: null,
      modalAudioInput: {
        id: null,
        word: null,
        plain: null,
        translation: null,
        transcription: null,
        classifications: null,
        audio: null,
        syllabus: null,
        syllabusAudio: null,
        pronounciation: null,
        video_url: null
      },
      alphabet: [
        {title: 'A', alternative: '<b>A'},
        {title: 'B', alternative: '<b>B'},
        {title: 'C', alternative: '<b>C'},
        {title: 'D', alternative: '<b>D'},
        {title: 'E', alternative: '<b>E'},
        {title: 'F', alternative: '<b>F'},
        {title: 'G', alternative: '<b>G'},
        {title: 'H', alternative: '<b>H'},
        {title: 'I', alternative: '<b>I'},
        {title: 'J', alternative: '<b>J'},
        {title: 'K', alternative: '<b>K'},
        {title: 'L', alternative: '<b>L'},
        {title: 'M', alternative: '<b>M'},
        {title: 'N', alternative: '<b>N'},
        {title: 'O', alternative: '<b>O'},
        {title: 'P', alternative: '<b>P'},
        {title: 'Q', alternative: '<b>Q'},
        {title: 'R', alternative: '<b>R'},
        {title: 'S', alternative: '<b>S'},
        {title: 'T', alternative: '<b>T'},
        {title: 'U', alternative: '<b>U'},
        {title: 'V', alternative: '<b>V'},
        {title: 'W', alternative: '<b>W'},
        {title: 'X', alternative: '<b>X'},
        {title: 'Y', alternative: '<b>Y'},
        {title: 'Z', alternative: '<b>Z'}
      ],
      prevIndex: null,
      selected: null
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
    setUpFileSyllabusUpload(e){
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
      fileReader.addEventListener('load', (e) => {
        this.audioFile = e.target.result
      })
    },
    submit(){
      const formData = new FormData()
      if(this.validate() === true){
        formData.append('word', this.word)
        formData.append('plain', this.plain)
        formData.append('translation', this.translation)
        formData.append('audio', this.audio)
        formData.append('syllabus', this.syllabus)
        formData.append('syllabus_audio', this.syllabusAudio)
        formData.append('accent_text', this.accentText)
        formData.append('transcription', this.transcription)
        formData.append('classifications', this.classifications)
        formData.append('pronounciation', this.pronounciation)
        formData.append('video_url', this.videoUrl)
        axios.post(CONFIG.BACKEND_URL + '/word_audio/create', formData).then(response => {
          if(response.data.error.status === 100){
            let error = response.data.error.message
            if(error.word !== 'undefined'){
              this.errorMessage = error.word[0]
            }else{
              this.errorMessage = JSON.stringify(error)
            }
          }else if(response.data.result){
            this.closeFag = true
            $('#editModal').modal('hide')
            this.retrieve(this.selected)
          }else{
            this.closeFag = false
          }
        })
      }else{
        this.errorMessage = 'Please fill up the required information.'
      }
    },
    retrieve(item){
      this.hideIPA()
      this.APIRequest('word_audio/retrieve_by_pagination', item).then(response => {
        this.audioList = response.data
        this.selected = item
      })
    },
    deleteWord(index){
      let parameter = {
        id: index
      }
      this.APIRequest('word_audio/delete', parameter).then(response => {
        this.retrieve(this.selected)
      })
    },
    play(path){
      this.audio = new Audio(CONFIG.BACKEND_URL + path)
      this.audio.play()
    },
    pause(path){
      this.audio.pause()
    },
    validate(){
      if(this.word === null || this.translation === null || this.plain === null){
        return false
      }else{
        return true
      }
    },
    editModal(index){
      this.modalAudio = this.audioList[index]
      this.modalAudioInput.id = this.modalAudio.id
    },
    updateWord(){
      axios.post(CONFIG.BACKEND_URL + '/word_audio/update', this.modalAudio).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data){
          this.closeFag = true
          $('#editModal').modal('hide')
          this.retrieve(this.selected)
        }else{
          this.closeFag = false
        }
      })
    },
    showIPA(index){
      if(this.prevIndex === null){
        this.prevIndex = index
        $('#ipa-' + index).css({'display': 'block'})
      }else{
        if(this.prevIndex !== null){
          $('#ipa-' + this.prevIndex).css({'display': 'none'})
          $('#ipa-' + index).css({'display': 'block'})
          this.prevIndex = index
        }else{
          $('#ipa-' + index).css({'display': 'none'})
          this.prevIndex = null
        }
      }
    },
    hideIPA(index){
      this.prevIndex = null
      $('#ipa-' + index).css({'display': 'none'})
    }
  }
}
</script>
