<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">SubCategory Lesson</li>
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
            <option v-for="item, index in category" class="form-control" v-bind:value="item.id"><label v-html="item.title"></label></option>
          </select>
          <br>
          <button class="btn btn-primary" v-on:click="filter()">Filter</button>
        </div>
        <br>
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Title</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="subCategory.length !== 0">
            <tr v-for="item, index in subCategory">
              <td>{{item.title}}</td>
              <td><!-- 
                <button class="btn btn-primary" v-on:click="play(item.path + item.audio)">Play</button>
                <button class="btn btn-danger" v-on:click="pause(item.path + item.audio)">Stop</button> -->
                <button class="btn btn-primary" v-on:click="updateModal(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteSubCategory(item.id)"><i class="fa fa-trash"></i>Delete</button>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalSubCategory !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Sub Category</h5>
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
            <input type="text" name="title" class="form-control" v-model="modalSubCategory.title">
            <br>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateSubCategory()" v-if="closeFag == false">update</button>
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
            <label>Category</label>
            <br>
            <select class="form-control" v-model="categoryId">
              <option v-for="item, index in category" v-bind:value="item.id"><label v-html="item.title"></label></option>
            </select>
            <label>Title</label>
            <br>
            <input type="text" name="title" class="form-control" placeholder="Title" v-model="title">
            <br>
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
    this.getCategory()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Sub Category',
      errorMessage: null,
      closeFag: false,
      title: null,
      category: [],
      subCategory: [],
      filterId: null,
      categoryId: null,
      modalSubCategory: null,
      modalSubCategoryInput: {
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
      formData.append('top_lesson_id', this.categoryId)
      axios.post(CONFIG.BACKEND_URL + '/sub_category/create', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.result){
          this.closeFag = true
          ROUTER.go('/subcategory_lesson')
        }else{
          this.closeFag = false
        }
      })
    },
    filter(id){
      let parameter = {
        'condition': [{
          'column': 'top_lesson_id',
          'clause': '=',
          'value': this.filterId
        }]
      }
      this.getSubCategory(parameter)
    },
    getSubCategory(parameter){
      this.APIRequest('sub_category/retrieve', parameter).then(response => {
        this.subCategory = response.data
      })
    },
    getCategory(){
      this.APIRequest('category/retrieve').then(response => {
        this.category = response.data
      })
    },
    deleteSubCategory(index){
      let parameter = {
        id: index
      }
      this.APIRequest('sub_category/delete', parameter).then(response => {
        ROUTER.go('/subcategory_lesson')
      })
    },
    validation(){
      if(this.original === null || this.translation === null){
        return false
      }else{
        return true
      }
    },
    updateModal(index){
      this.modalSubCategory = this.subCategory[index]
      this.modalSubCategoryInput.id = this.modalSubCategory.id
    },
    updateSubCategory(){
      // let formData = new FormData()
      // formData.append('id', this.modalSubCategoryInput.id)
      // if(this.modalSubCategoryInput.original !== null){
      //   formData.append('title', this.modalSubCategoryInput.title)
      // }else{
      //   //
      // }
      axios.post(CONFIG.BACKEND_URL + '/sub_category/update', this.modalSubCategory).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.closeFag = true
          ROUTER.go('/subcategory_lesson')
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
