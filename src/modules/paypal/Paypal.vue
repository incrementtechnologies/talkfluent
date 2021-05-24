<template>
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
        <li class="breadcrumb-item active">Audio File Management</li>
      </ul>
    </div>
    <div class="col-lg-10 mx-auto">
      
    </div>
  </div>
</template>
<style scoped>
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    AUTH.redirect()
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
