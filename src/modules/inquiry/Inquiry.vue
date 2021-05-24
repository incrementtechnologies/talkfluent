<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">Inquiries</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto history"> 
        <br>
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Full Name</td>
              <td>Email</td>
              <td>Subject</td>
              <td>Message</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item, index in data"  v-if="data !== null">
              <td>{{item.fullname}}</td>
              <td>{{item.email}}</td>
              <td v-html="item.subject"></td>
              <td>{{item.message}}</td>
              <td>
                <i class="fa fa-trash text-danger action-link" v-on:click="deleteModule(item.id)"></i>
              </td>
            </tr>
            <tr v-else>
              <td colspan="6" class="text-danger text-center">Empty Inquiries</td>
            </tr>
          </tbody>
        </table>
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
      tokenData: AUTH.tokenData,
      data: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveModule(){
      let parameter = {
        'sort': {
          'id': 'DESC'
        }
      }
      this.APIRequest('contacts/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    },
    deleteModule(id){
      let parameter = {
        id: id
      }
      this.APIRequest('contacts/delete', parameter).then(response => {
        ROUTER.go('/blog')
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

#textarea{
  min-height: 300px;
}
</style>
