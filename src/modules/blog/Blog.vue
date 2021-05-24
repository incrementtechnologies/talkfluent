<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Blog Management</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Post</button>
      </div>
      <div class="col-lg-10 mx-auto history"> 
        <br>
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Image</td>
              <td>Title</td>
              <td>Tag</td>
              <td>Content</td>
              <td>Status</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item, index in data"  v-if="data !== null">
              <td>
                <img v-bind:src="config.BACKEND_URL + item.featured_image" height="50px" width="50px" style="border-radius: 50%;">
              </td>
              <td>{{item.title}}</td>
              <td>{{item.tag}}</td>
              <td v-html="item.content"></td>
              <td>{{item.status}}</td>
              <td>
                <i class="fa fa-pencil text-warning action-link" data-toggle="modal" data-target="#editModal" v-on:click="editModule(index)"></i>
                <i class="fa fa-trash text-danger action-link" v-on:click="deleteModule(item.id)"></i>
              </td>
            </tr>
            <tr v-else>
              <td colspan="6" class="text-danger text-center">Empty Lesson</td>
            </tr>
          </tbody>
        </table>
      </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalEdit !== null">
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
            <br>
            <label>Title</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Title" v-model="modalEdit.title">
            <br>
            <label>Content</label>
            <br> 
            <textarea class="form-control" v-model="modalEdit.content" id="textarea"></textarea>
            <br>
            <label>Tags</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Title" v-model="modalEdit.tag">
            <br>
            <label>Featured Image</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Featured Image URL" v-model="modalEdit.featured_image">
            <br>
            <label>Status</label>
            <br>
            <select class="form-control" v-model="modalEdit.status">
              <option value="PENDING">PENDING</option>
              <option value="PUBLISHED">PUBLISHED</option>
            </select>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateModule()">Update</button>
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
            <br>
            <label>Title</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Title" v-model="input.title">
            <br>
            <label>Content</label>
            <br>  
            <textarea class="form-control" v-model="input.content" id="textarea"></textarea>
            <br>
            <label>Tags</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Title" v-model="input.tag">
            <br>
            <label>Featured Image</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Featured Image URL" v-model="input.featured_image">
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
    this.retrieveModule()
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Post',
      data: null,
      tags: [],
      input: {
        title: null,
        content: null,
        tag: null,
        status: 'PENDING',
        featured_image: null
      },
      errorMessage: null,
      closeFag: false,
      modalEdit: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    submit(){
      if(this.validation() === true){
        this.errorMessage = null
        this.createModule()
      }else{
        this.errorMessage = 'Please fillup the required information'
      }
    },
    retrieveModule(){
      this.APIRequest('blogs/retrieve', {}).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    },
    createModule(){
      this.APIRequest('blogs/create', this.input).then(response => {
        if(response.result > 0){
          ROUTER.go('/blog')
        }
      })
    },
    editModule(index){
      this.modalEdit = this.data[index]
    },
    updateModule(){
      this.APIRequest('blogs/update', this.modalEdit).then(response => {
        if(response.data === true){
          ROUTER.go('/blog')
        }
      })
    },
    deleteModule(id){
      let parameter = {
        id: id
      }
      this.APIRequest('blogs/delete', parameter).then(response => {
        ROUTER.go('/blog')
      })
    },
    validation(){
      if(this.input.title === null || this.input.status === null || this.input.tag === null || this.input.content === null || this.input.featured_image === null){
        return false
      }else{
        return true
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

#textarea{
  min-height: 300px;
}
</style>
