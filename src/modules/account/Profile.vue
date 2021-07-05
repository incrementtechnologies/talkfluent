<template>
  <div class="holder">
    <span class="header">Profile</span>
    <span class="content">
      <span class="inputs" v-if="data !== null">
         <span class="error text-danger" v-if="errorMessage !== null">
          <b>Oops!</b> {{errorMessage}}
        </span>
        <span class="error text-success" v-if="successMessage !== null">
          {{successMessage}}
        </span>
        <div class="form-group" style="margin-top: 25px;">
          <label for="address">First Name <label class="text-danger">*</label></label>
          <input type="text" class="form-control" placeholder="Enter First Name" v-model="data.first_name">
        </div>

     <!--    <div class="form-group">
          <label for="address">Middle Name</label>
          <input type="text" class="form-control" placeholder="Optional" v-model="data.middle_name">
        </div>
 -->
        <div class="form-group">
          <label for="address">Last Name <label class="text-danger">*</label></label>
          <input type="text" class="form-control" placeholder="Enter Last Name" v-model="data.last_name">
        </div>

        <div class="form-group">
          <label for="address">Gender <label class="text-danger">*</label></label>
          <select class="form-control" v-model="data.sex">
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>

        <div class="form-group">
          <label for="address">Phone Number</label>
          <input type="text" class="form-control" placeholder="Optional" v-model="data.cellular_number">
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" class="form-control" placeholder="Optional" v-model="data.address">
        </div>
<!-- 
        <div class="form-group">
          <label for="address">Birthdate</label>
          <input type="date" class="form-control" v-model="data.birth_date" placeholder="Optional">
        </div> -->

        <button class="btn btn-primary" style="margin-bottom: 25px;" @click="update()">Update</button>
      
      </span>
      <span class="sidebar">
        <span class="sidebar-header" style="margin-top: 25px;">Profile Picture</span>
        <span class="image" v-if="user.profile !== null">
          <img :src="config.BACKEND_URL + user.profile.url" height="auto" width="100%" >
        </span>
        <span class="image" v-else>
          <i class="fa fa-user-circle-o" ></i>
        </span>
        <button class="btn btn-primary custom-block" style="margin-top: 5px; margin-bottom: 25px;" @click="addImage()">Upload New Profile
          <input type="file" id="profilePicture" accept="image/*" @change="setupFile($event)">
        </button>
      </span>
    </span>
  </div>
</template>
<style scoped>
.holder{
  width: 95%;
  float: left;
  margin-left: 5%;
}

.error{
  min-height: 50px;
  overflow-y: hidden;
  float: left;
  width: 100%;
}
.header{
  width: 100%;
  height: 50px;
  line-height: 50px;
  font-size: 24px;
  border-bottom: solid 1px #ddd;
  float: left;
}
.content{
  width: 100%;
  min-height: 50px;
  float: left;
  overflow-y: hidden;
}
.inputs{
  width: 65%;
  float: left;
  margin-right: 5%;
  min-height: 50px;
  overflow-y: hidden;
}
.content .error{
  width: 100%;
  float: left;
  height: 40px !important;
  text-align: left !important;
  line-height: 40px !important;
  margin: 0% !important;
}
.sidebar{
  width: 30%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}
.sidebar-header{
  height: 40px;
  line-height: 40px;
  width: 100%;
  float: left;
}
.sidebar .image{
  width: 100%;
  float: left;
  min-height: 200px;
  overflow-y: hidden;
  text-align: center;
}
.image i{
  font-size: 150px;
  line-height: 200px;
}
.image img{
  border-radius: 5px;
}
.custom-block{
  width: 100%;
  float: left;
  height: 45px !important;
}
.custom-block input{
  display: none;
}

@media screen and (max-width: 992px){
  .holder{
    width: 96%;
    margin-left: 2%;
    margin-right: 2%;
  }
  .sidebar, .inputs{
    width: 100%;
    margin-right: 0%;
    margin-left: 0%;
  }
}
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      file: null,
      data: null,
      errorMessage: null,
      successMessage: null
    }
  },
  methods: {
    addImage(){
      $('#profilePicture')[0].click()
    },
    setupFile(event){
      let files = event.target.files || event.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.file = files[0]
        this.createFile(files[0])
      }
    },
    createFile(file){
      let fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
      this.upload()
    },
    upload(){
      let formData = new FormData()
      formData.append('file', this.file)
      formData.append('file_url', this.file.name)
      formData.append('account_id', this.user.userID)
      $('#loading').css({display: 'block'})
      axios.post(this.config.BACKEND_URL + '/account_profiles/create', formData).then(response => {
        if(response.data.data > 0){
          AUTH.checkAuthentication(null)
          $('#loading').css({display: 'none'})
        }
      })
    },
    retrieve(){
      let parameter = {
        condition: [{
          value: this.user.userID,
          column: 'account_id',
          clause: '='
        }]
      }
      this.APIRequest('account_informations/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data[0]
        }else{
          this.data = null
        }
      })
    },
    update(){
      if(this.validate()){
        this.APIRequest('account_informations/update', this.data).then(response => {
          if(response.data === true){
            this.retrieve()
            this.errorMessage = null
            this.successMessage = 'Successfully Updated.'
          }else{
            this.errorMessage = 'Unable to Update! Please contact the administrator.'
            this.successMessage = null
          }
        })
      }
    },
    validate(){
      let i = this.data
      if(i.first_name !== null && i.first_name !== '' && i.last_name !== null && i.last_name !== '' && i.sex !== null && i.sex !== ''){
        return true
      }else{
        this.successMessage = null
        this.errorMessage = 'Please fill up all required fields.'
        return false
      }
    }
  }
}
</script>
