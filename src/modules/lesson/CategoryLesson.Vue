<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Category Lesson</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Audio</button>
      </div>
      <div class="col-lg-10 mx-auto history">
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Title</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="category !== null">
            <tr v-for="item, index in category">         
              <td>{{item.title}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="editModal(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteCategory(item.id)"><i class="fa fa-trash"></i>Delete</button>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalCategory !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Category</h5>
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
              <input type="text" name="title" class="form-control" v-model="modalCategory.title">          
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
              <label>Title</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Title" v-model="title">
              <br>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFlag == false">Submit</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
            </div>
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
    this.getCategory()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Category',
      category: [],
      title: null,
      errorMessage: null,
      modalCategory: null,
      closeFlag: false,
      modalCategoryInput: {
        id: null,
        title: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    submit(){
      const formData = new FormData()
      if(this.validate() === true){
        formData.append('title', this.title)
        axios.post(CONFIG.BACKEND_URL + '/category/create', formData).then(response => {
          console.log(response.data)
          if(response.data.error.status === 100){
            let error = response.data.error.message
            if(error.word !== 'undefined'){
              this.errorMessage = error.word[0]
            }else{
              this.errorMessage = JSON.stringify(error)
            }
          }else if(response.data.result){
            this.closeFag = true
            ROUTER.go('/category_lesson')
          }else{
            this.closeFag = false
          }
        })
      }else{
        this.errorMessage = 'Please fill up the required information.'
      }
    },
    getCategory(){
      this.APIRequest('category/retrieve').then(response => {
        this.category = response.data
      })
    },
    deleteCategory(id){
      let parameter = {
        id: id
      }
      this.APIRequest('category/delete', parameter).then(response => {
        ROUTER.go('/category_lesson')
      })
    },
    validate(){
      if(this.word === null || this.translation === null){
        return false
      }else{
        return true
      }
    },
    editModal(index){
      this.modalCategory = this.category[index]
      this.modalCategoryInput.id = this.modalCategory.id
    },
    updateWord(){
      // const formData = new FormData()
      // formData.append('id', this.modalCategoryInput.id)
      // if(this.modalCategoryInput.title !== null){
      //   formData.append('title', this.modalCategoryInput.title)
      // }
      axios.post(CONFIG.BACKEND_URL + '/category/update', this.modalCategory).then(response => {
        console.log(response.data)
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data){
          this.closeFag = true
          ROUTER.go('/category_lesson')
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
