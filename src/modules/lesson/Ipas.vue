<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">IPA Content</li>
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
              <td>Action</td>
              <td>Title</td>
              <td>Code</td>
              <td>Bottom</td>
              <td>Text</td>
              <td>Category</td>
              <td>Audio</td>
              <td>Video</td>
              <td>Image</td>
            </tr>
          </thead>
          <tbody v-if="data !== null">
            <tr v-for="item, index in data">
              <td><!-- 
                <button class="btn btn-primary" v-on:click="play(item.path + item.audio)">Play</button>
                <button class="btn btn-danger" v-on:click="pause(item.path + item.audio)">Stop</button> -->
                <button class="btn btn-primary" v-on:click="setModalIpas(item)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteItem(item)"><i class="fa fa-trash"></i>Delete</button>
              </td>
              <td>{{item.title}}({{item.subtitle}})</td>
              <td>{{item.code}}</td>
              <td>{{item.bottom}}</td>
              <td>{{item.text}}</td>
              <td>{{item.category}}</td>
              <td>{{item.audio}}</td>
              <td>{{item.image}}</td>
              <td>{{item.video}}</td>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalIpa !== null">
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
            <label>Title</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalIpa.title">
            <br>
            <label>SubTitle</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="modalIpa.subtitle">
            <br>
            <label>Code</label>
            <br>
            <input type="text" name="code" class="form-control" v-model="modalIpa.code">
            <br>
            <label>Bottom</label>
            <br>
            <input type="text" name="code" class="form-control" v-model="modalIpa.bottom">
            <br>
            <label>Text</label>
            <br>
            <textarea class="form-control" placeholder="IPA Text" v-model="modalIpa.text"></textarea>
            <br>
            <label>Category</label>
            <br>
            <select class="form-control" v-model="modalIpa.category">
              <option value="consonant">Consonant</option>
              <option value="vowel">Vowel</option>
              <option value="semivowel">Semi-Vowel</option>
              <option value="diphthong">Diphthong</option>
              <option value="triphthong">Triphthong</option>
              <option value="hiatus">Hiatus</option>
            </select>
            <br>
            <label>Audio</label>
            <br>
            <input type="text" name="audiourl" class="form-control" placeholder="Audio url" v-model="modalIpa.audio">
            <br>
            <label>Image</label>
            <br>
            <input type="text" name="videourl" class="form-control" placeholder="Image url" v-model="modalIpa.image">
            <br>
            <label>Video</label>
            <br>
            <input type="text" name="videourl" class="form-control" placeholder="Video url" v-model="modalIpa.video">
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
            <label>Title</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="newIpa.title">
            <br>
            <label>SubTitle</label>
            <br>
            <input type="text" name="title" class="form-control" v-model="newIpa.subtitle">
            <br>
            <label>Code</label>
            <br>
            <input type="text" name="code" class="form-control" v-model="newIpa.code">
            <br>
            <label>Bottom</label>
            <br>
            <input type="text" name="code" class="form-control" v-model="newIpa.bottom">
            <br>
            <label>Text</label>
            <br>
            <textarea class="form-control" placeholder="IPA Text" v-model="newIpa.text"></textarea>
            <br>
            <label>Category</label>
            <br>
            <select class="form-control" v-model="newIpa.category">
              <option value="consonant">Consonant</option>
              <option value="vowel">Vowel</option>
              <option value="semivowel">Semi-Vowel</option>
              <option value="diphthong">Diphthong</option>
              <option value="triphthong">Triphthong</option>
              <option value="hiatus">Hiatus</option>
            </select>
            <br>
            <label>Audio</label>
            <br>
            <input type="text" name="audiourl" class="form-control" placeholder="Audio url" v-model="newIpa.audio">
            <br>
            <label>Image</label>
            <br>
            <input type="text" name="videourl" class="form-control" placeholder="Image url" v-model="newIpa.image">
            <br>
            <label>Video</label>
            <br>
            <input type="text" name="videourl" class="form-control" placeholder="Video url" v-model="newIpa.video">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="create()">Submit</button>
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
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add IPA',
      errorMessage: null,
      config: CONFIG,
      data: null,
      modalIpa: null,
      newIpa: {
        title: null,
        subtitle: null,
        code: null,
        bottom: null,
        text: null,
        category: null,
        audio: null,
        video: null,
        image: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    create(){
      this.APIRequest('ipas/create', this.newIpa).then(response => {
        if(response.data > 0){
          this.retrieve()
          $('#myModal').modal('hide')
          this.clear()
        }
      })
    },
    retrieve(){
      let parameter = {
        sort: {
          'title': 'asc'
        }
      }
      this.APIRequest('ipas/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    },
    setModalIpas(item){
      this.modalIpa = item
    },
    update(){
      this.APIRequest('ipas/update', this.modalIpa).then(response => {
        if(response.data === true){
          this.newIpa = response.data
          this.retrieve(this.modalIpa.word_audio_id)
          $('#editModal').modal('hide')
        }
      })
    },
    deleteItem(item){
      let parameter = {
        id: item.id
      }
      this.APIRequest('ipas/delete', parameter).then(response => {
        this.retrieve(item.word_audio_id)
      })
    },
    clear(){
      this.newIpa.title = null
      this.newIpa.code = null
      this.newIpa.bottom = null
      this.newIpa.text = null
      this.newIpa.category = null
      this.newIpa.audio = null
      this.newIpa.image = null
      this.newIpa.video = null
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
