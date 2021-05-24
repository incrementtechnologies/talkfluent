<template>
  <div class="holder" v-if="data !== null">
    <span class="header">Billing Information</span>
    <span class="content">
      <span class="error text-danger" v-if="errorMessage !== null">
        <b>Oops!</b> {{errorMessage}}
      </span>
      <span class="error text-success" v-if="successMessage !== null">
        {{successMessage}}
      </span>
      <span class="inputs">
        <div class="form-group" style="margin-top: 25px;">
          <label for="address">Company</label>
          <input type="text" class="form-control" placeholder="Optional" v-model="data.company">
        </div>

        <div class="form-group">
          <label for="address">Address <label class="text-danger">*</label></label>
          <input type="text" class="form-control" placeholder="Enter Company Address" v-model="data.address">
        </div>

        <div class="row">
          <div class="form-group left col-lg-8 col-md-8 col-sm-12">
            <label for="address">City <label class="text-danger">*</label></label>
            <input type="text" class="form-control" placeholder="Enter City" v-model="data.city">
          </div>

          <div class="form-group right col-lg-4 col-md-4 col-sm-12">
            <label for="address">Postal Code <label class="text-danger">*</label></label>
            <input type="text" class="form-control" placeholder="Enter Postal Code" v-model="data.postal_code">
          </div>
        </div>

        <div class="row">
          <div class="form-group left col-lg-8 col-md-8 col-sm-12">
            <label for="address">Country <label class="text-danger">*</label></label>
            <select class="form-control" v-model="data.country" v-on:change="getStates()">
              <option v-bind:value="item.countryCode" v-for="item in countries">{{item.name}}</option>
            </select>
          </div>

          <div class="form-group right col-lg-4 col-md-4 col-sm-12">
            <label for="address">State <label class="text-danger">*</label></label>
            <select class="form-control" v-model="data.state" v-if="states !== null">
              <option v-bind:value="item.label" v-for="item, index in states" v-if="item.label !== null && item.label !== 'undefined' && item.label !== undefined">{{item.label}}</option>
            </select>
            <input type="text" class="form-control form-control-login" v-model="data.state" v-else placeholder="Enter State or Province">
          </div>
        </div>
        
        <button class="btn btn-primary" style="margin-bottom: 25px;" @click="update()">Update</button>
      </span>
      <span class="sidebar">
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
.content .error{
  width: 100%;
  float: left;
  height: 40px !important;
  text-align: left !important;
  line-height: 40px !important;
  margin: 0% !important;
}
.inputs{
  width: 65%;
  float: left;
  margin-right: 5%;
  min-height: 50px;
  overflow-y: hidden;
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
}
.custom-block input{
  display: none;
}
.row{
  margin: 0px !important;
}
.row .left{
  padding-left: 0px !important;
}
.row .right{
  padding-right: 0px !important;
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
  .row .form-group{
    padding-left: 0px !important;
    padding-right: 0px !important;
  }
}
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
import EasyCountriesList from 'easy-countries-list'
export default {
  mounted(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      data: null,
      errorMessage: null,
      successMessage: null,
      countries: EasyCountriesList.getAllCountries(),
      states: null
    }
  },
  methods: {
    retrieve(){
      let parameter = {
        condition: [{
          value: this.user.userID,
          column: 'account_id',
          clause: '='
        }]
      }
      this.APIRequest('billing_informations/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data[0]
        }else{
          this.data = null
        }
      })
    },
    getStates(){
      let key = 'd1c4bd17024a50b0f5e08a55b902c817'
      let ID = 254
      axios.get('https://ezcmd.com/apps/api_ezhigh/get_hierarchy/' + key + '/' + ID + '?country_code=' + this.data.country + '&level=1').then(response => {
        this.states = response.data
      })
    },
    update(){
      if(this.validate()){
        this.APIRequest('billing_informations/update', this.data).then(response => {
          if(response.data === true){
            this.retrieve()
            this.successMessage = 'Successfully Updated!'
            this.errorMessage = null
          }else{
            this.successMessage = null
            this.errorMessage = 'Unable to Update! Please contact the administrator.'
          }
        })
      }
    },
    validate(){
      let temp = this.data
      if(temp.address !== null && temp.address !== '' && temp.city !== null && temp.city !== '' && temp.postal_code !== null && temp.postal_code !== '' && temp.country !== null && temp.country !== '' && temp.states !== null && temp.states !== ''){
        return true
      }else{
        this.errorMessage = 'Please fill up all required fields.'
        return false
      }
    }
  }
}
</script>
