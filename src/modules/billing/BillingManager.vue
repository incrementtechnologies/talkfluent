<template>
	<div class="row">
		<div class="col-lg-10 mx-auto">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
        <li class="breadcrumb-item active">Billing Manager</li>
      </ul>
    </div>
    <div class="col-lg-10 mx-auto">
    	<button class="btn btn-primary pull-right" @click="run()">Scan</button>
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
  created(){
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      tokenData: AUTH.tokenData
    }
  },
  components: {
  },
  methods: {
    redirect(path){
      ROUTER.push(path)
    },
    run(){
      this.APIRequest('billing_managers/run', {}).then(response => {
      })
    }
  }
}
</script>

