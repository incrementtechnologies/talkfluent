<template>
	<div class="row">
		<div class="col-lg-10 mx-auto">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
        <li class="breadcrumb-item active">Billing Manager</li>
      </ul>
    </div>
    <div class="col-lg-10 mx-auto">
      <div class="form-group">
        <label for="date">Date</label>
        <input type="date" class="form-control col-lg-4" v-model="date">
        <button class="btn btn-primary" style="margin-top: 5px;" @click="filter()">Filter</button>
      </div>
      
    </div>
    <div class="col-lg-10 mx-auto">
      <table class="table table-responsive">
        <thead>
          <tr>
            <td>Account ID</td>
            <td>Payload</td>
            <td>Message</td>
            <td>Date</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item, index in data" v-if="data !== null">
            <td>{{item.account_id}}</td>
            <td>{{item.payload}}</td>
            <td>{{item.message}}</td>
            <td>{{item.created_at}}</td>
          </tr>
          <tr v-if="data === null">
            <td class="text-center text-danger" colspan="4">No Results!</td>
          </tr>
        </tbody>
      </table>
    </div>
	</div>
</template>
<style>
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
import {Howl, Howler} from 'howler'
import OPKEYS from '../../payment.js'
import PRODUCTS from '../../products.js'
export default {
  mounted(){
    AUTH.redirect()
    this.defaultParameter()
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      tokenData: AUTH.tokenData,
      data: null,
      date: null
    }
  },
  components: {
  },
  methods: {
    redirect(path){
      ROUTER.push(path)
    },
    defaultParameter(){
      let parameter = {
        date: null
      }
      this.retrieve(parameter)
    },
    retrieve(parameter){
      this.APIRequest('loggers/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    },
    filter(){
      if(this.date !== null && this.date !== ''){
        let parameter = {
          date: this.date
        }
        this.retrieve(parameter)
      }else{
        let parameter = {
          date: null
        }
        this.retrieve(parameter)
      }
    }
  }
}
</script>

