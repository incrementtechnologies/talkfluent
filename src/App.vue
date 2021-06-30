<template>
  <div id="app">
    <div v-bind:style="(globalVariables.showModal) ? 'overflow-y:hidden; height:'+deviceHeight+'px!important': ''" v-if="tokenData.loading == false">
      <div v-if="tokenData.token !== null && user.userID > 0">
       <system-header></system-header>
       <system-sidebar></system-sidebar>
      </div>
      <div v-else>
        <login-header></login-header>
        <system-content></system-content>
      </div>
    </div>
    <div v-if="tokenData.loading == true" class="empty">
      
    </div>
    <system-footer></system-footer>
    <system-loading></system-loading>
  </div>
</template>
<script>
import ROUTER from './router'
import AUTH from './services/auth'
import global from './helpers/global'
import CONFIG from 'config.js'
export default {
  name: 'app',
  mounted(){
    this.token = localStorage.getItem('usertoken')
    this.accountId = localStorage.getItem('account_id')
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      tokenData: AUTH.tokenData,
      currentRoute: ROUTER.currentRoute.name,
      deviceHeight: document.documentElement.clientHeight,
      appGlobal: {
        showModal: false
      },
      globalVariables: global,
      token: null,
      accountId: null
    }
  },
  head: {
    title: {
      inner: 'Hi',
      separator: ' ',
      complement: ' '
    }
  },
  methods: {
    logOut(){
      AUTH.deaunthenticate()
      ROUTER.go('/')
    }
  },
  components: {
    'login-header': () => import('modules/frame/LoginHeader.vue'),
    'system-header': () => import('modules/frame/Header.vue'),
    'system-sidebar': () => import('modules/frame/Sidebar.vue'),
    'system-content': () => import('modules/frame/Content.vue'),
    'system-footer': () => import('modules/frame/Footer.vue'),
    'system-loading': () => import('components/loader/Loading.vue')
  }
}
</script>

<style>
.half-width{
  width: 50%
}
.push-half-right{
  margin-left: 50%
}
.nav a{
  font-size: 15px
}
.dropdown-menu li a{
  padding: 10px;
}
.container {
   min-height:100%;
   position:relative;
}
.empty{
  height: 100vh;
  width: 100%;
  float: left;
}
</style>
