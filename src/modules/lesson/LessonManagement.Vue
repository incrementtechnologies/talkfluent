<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Lesson Management</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Lesson</button>
      </div>
      <div class="col-lg-10 mx-auto history"> 
        <div class="filter">
          <label><h5>Filter</h5></label>
          <select class="form-control col-lg-3 col-md-4 col-sm-12" v-model="filterId">
            <option disabled value="">Please Select Sub Category</option>
            <option v-for="item, index in subCategory" class="form-control" v-bind:value="item.id"><label v-html="item.title"></label></option>
          </select>
          <br>
          <button class="btn btn-primary" v-on:click="filter()">Filter</button>
        </div>
        <br>
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Audio</td>
              <td>Lesson</td>
              <td>Title</td>
              <td>Culture Tip</td>
              <td>Grammar Tip</td>
              <td>Status</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item, index in lesson"  v-if="lesson !== null">
              <td>
                <button class="btn btn-primary" v-on:click="play(item.lesson_audio)">Play</button>
              </td>
              <td>Lesson # {{index + 1}}</td>
              <td>{{item.title}}</td>
              <td>
                  <button class="btn btn-primary" v-on:click="play(item.culture_audio)">Play</button>
                  <!-- <label>{{item.culture_tip}}</label> -->
              </td>
              <td> 
                <button class="btn btn-primary" v-on:click="play(item.grammar_audio)">Play</button>
                <!-- <label>{{item.grammar_tip}}</label> -->
              </td>
              <td>{{item.status}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="editLesson(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteLesson(item.id)"><i class="fa fa-trash"></i>Delete</button>
              </td>
            </tr>
            <tr v-else>
              <td colspan="6" class="text-danger text-center">Empty Lesson</td>
            </tr>
          </tbody>
        </table>
      </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalLesson !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Edit Lesson</h5>
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
            <input type="text" name="title" class="form-control" v-model="modalLesson.title">
            <br>
            <label>Lesson Audio(Spanish)</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalLesson.lesson_audio">
            <br>
            <label>Lesson Audio(English)</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalLesson.english_audio">
            <br>
            
            <div>
              <br>
              <label>Video URL(Optional)</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="modalLesson.video_url"></textarea>
            </div>

            <div>
              <h5>Additional Information</h5>
              <br>
              <label>Grammar Tip</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="modalLesson.grammar_tip"></textarea>
              <br>
              <label>Grammar Audio(Optional)</label>
              <input type="text" name="title" class="form-control" v-model="modalLesson.grammar_audio">
              <br>
              <label>Culture Tip</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="modalLesson.culture_tip"></textarea>
              <br>
              <label>Culture Audio(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control"  v-model="modalLesson.culture_audio">
              
            </div>
            <div>
              <br>
              <h5>Grammar Test</h5>
              <br>
              <label>Grammar Test Audio(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control"  v-model="modalLesson.grammar">
              <br>
              <label>Grammar Test Content</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="modalLesson.grammar_test_content"></textarea>
              <br>
              <label>Grammar Test Content Audio</label>
              <br>
              <input type="text" name="title" class="form-control"  v-model="modalLesson.grammary_test_content_audio">
              <br>
              <label>Grammar Test Question</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="modalLesson.grammar_question"></textarea>
            </div>

            <div>
              <label>Lesson Status</label>
              <select v-model="modalLesson.status" class="form-control">
                <option class="form-control" value="PENDING">PENDING</option>
                <option class="form-control" value="APPROVED">APPROVED</option>
              </select>
            </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateLesson()">Update</button>
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
            <br v-if="errorMessage !== null"><label>Sub Category</label>
            <br>
            <select class="form-control" v-model="subCategoryId">
              <option v-for="item, index in subCategory" v-bind:value="item.id"><label v-html="item.title"></label></option>
            </select>
            <br>
            <label>Title</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Title" v-model="title">
            <br>
            <label>Lesson Audio(Spanish)</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Lesson Audio URL(Spanish)" v-model="lessonAudio">
            <br>
            <label>Lesson Audio(English)</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Lesson Audio URL(English)" v-model="lessonAudioEnglish">
            <div>
              <br>
              <label>Video URL(Optional)</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="videoUrl"></textarea>
            </div>
            <br>
            <div>
              <h5>Additional Information</h5>
              <br>
              <label>Grammar Tip(Optional)</label>
              <textarea  id="accent-text" class="form-control" placeholder="Grammar Tip" v-model="grammarTip"></textarea>
              <br>
              <label>Grammar Audio(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Grammar Audio URL" v-model="grammarAudio">
              <br>
              <label>Culture Tip(Optional)</label>
              <textarea  id="accent-text" class="form-control" placeholder="Culture Tip" v-model="cultureTip"></textarea>
              <br>
              <label>Culture Audio(Optional)</label>
              <input type="text" name="title" class="form-control" placeholder="Culture Audio URL" v-model="cultureAudio">
            </div>
            <div>
              <br>
              <h5>Grammar Test</h5>
              <br>
              <label>Grammar Test Audio(Optional)</label>
              <br>
              <input type="text" name="title" class="form-control"  v-model="grammar">
              <br>
              <label>Grammar Test Content</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="grammarTestContent"></textarea>
              <br>
              <label>Grammar Test Content Audio</label>
              <br>
              <input type="text" name="title" class="form-control"  v-model="grammarTestContentAudio">
              <br>
              <label>Grammar Test Question</label>
              <br>
              <textarea  id="accent-text" class="form-control" v-model="grammarTestQuestion"></textarea>
            </div>
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
import {Howl, Howler} from 'howler'
export default {
  mounted(){
    AUTH.redirect()
    this.getSubCategory()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Lesson',
      subCategory: [],
      filterId: null,
      subCategoryId: null,
      title: null,
      fileCount: 0,
      lesson: [],
      lessonAudio: null,
      lessonAudioEnglish: null,
      cultureTip: null,
      grammarTip: null,
      cultureAudio: null,
      videoUrl: null,
      grammarAudio: null,
      grammar: null,
      grammarTestContent: null,
      grammarTestContentAudio: null,
      grammarTestQuestion: null,
      errorMessage: null,
      closeFag: false,
      modalIndex: null,
      modalLessonInput: {
        id: null,
        title: null,
        lessonAudio: null,
        cultureTip: null,
        cultureAudio: null,
        grammarTip: null,
        grammarAudio: null,
        grammar: null,
        grammar_test_content: null,
        videoUrl: null,
        status: null
      },
      modalLesson: null,
      audio: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    setupLessonAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.lessonFile = files[0]
        this.createFile(files[0])
      }
    },
    setupAccentAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.accentAudioFile = files[0]
        this.createFile(files[0])
      }
    },
    setupGrammarAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.grammarAudioFile = files[0]
        this.createFile(files[0])
      }
    },
    setupCultureAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.cultureAudioFile = files[0]
        this.createFile(files[0])
      }
    },
    setupAccentVideoFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.accentVideoFile = files[0]
        this.createFile(files[0])
      }
    },
    createFile(file){
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
      formData.append('title', this.title)
      formData.append('status', 'PENDING')
      formData.append('category_lesson_id', this.subCategoryId)
      if(this.lessonAudio !== null){
        formData.append('lesson_audio', this.lessonAudio)
      }
      if(this.lessonAudioEnglish !== null){
        formData.append('english_audio', this.lessonAudioEnglish)
      }
      if(this.cultureTip !== null){
        formData.append('culture_tip', this.cultureTip)
      }
      if(this.cultureAudio !== null){
        formData.append('culture_audio', this.cultureAudio)
      }
      if(this.grammarTip !== null){
        formData.append('grammar_tip', this.grammarTip)
      }
      if(this.grammarAudio !== null){
        formData.append('grammar_audio', this.grammarAudio)
      }
      if(this.grammar !== null){
        formData.append('grammar', this.grammar)
      }
      if(this.grammarTestContent !== null){
        formData.append('grammar_test_content', this.grammarTestContent)
      }
      if(this.grammarTestQuestion !== null){
        formData.append('grammar_question', this.grammarTestQuestion)
      }
      if(this.grammarTestContentAudio !== null){
        formData.append('grammary_test_content_audio', this.grammarTestContentAudio)
      }if(this.videoUrl !== null){
        formData.append('video_url', this.videoUrl)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/lesson/create', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          this.errorMessage = JSON.stringify(error)
        }else if(response.data.result){
          this.closeFag = true
          $('#myModal').modal('hide')
          this.filter()
        }else{
          this.closeFag = false
        }
      })
    },
    getSubCategory(){
      this.APIRequest('sub_category/retrieve').then(response => {
        this.subCategory = response.data
      })
    },
    filter(){
      let parameter = {
        'condition': [{
          'column': 'category_lesson_id',
          'clause': '=',
          'value': this.filterId
        }]
      }
      this.getLesson(parameter)
    },
    getLesson(parameter){
      this.lesson = null
      this.APIRequest('lesson/retrieve', parameter).then(response => {
        this.lesson = response.data
      })
    },
    editLesson(index){
      this.modalLesson = this.lesson[index]
      this.modalLessonInput.id = this.modalLesson.id
    },
    updateLesson(){
      axios.post(CONFIG.BACKEND_URL + '/lesson/update', this.modalLesson).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.closeFag = true
          $('#editModal').modal('hide')
          this.filter()
        }else{
          this.closeFag = false
        }
      })
    },
    deleteLesson(id){
      let parameter = {
        id: id
      }
      this.APIRequest('lesson/delete', parameter).then(response => {
        this.filter()
      })
    },
    validation(){
      if(this.title === null || this.grammarTip === null || this.cultureTip === null){
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
