<template>
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
        <li class="breadcrumb-item active">My Profile</li>
      </ul>
    </div>
    <div class="col-lg-10 mx-auto custom-division">
      <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{title}}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <span class="dropdown-item" v-for="item, index in menu" @click="makeActive(index)">{{item.title}}</span>
        </div>
      </div>
      <div class="sidebar">
        <!-- <span class="header">
          Personal Settings
        </span> -->
        <span class="item" v-bind:class="{'make-active': item.flag === true}" v-for="item, index in menu" v-if="menu !== null" @click="makeActive(index)">
          {{item.title}}
        </span>
      </div>
      <div class="content">
        <profile v-if="menu[0].flag === true"></profile>
        <account v-if="menu[1].flag === true"></account>
        <billing v-if="menu[2].flag === true"></billing>
      </div>
    </div>
  </div>

</template>
<style scoped>
.breadcrumb{
  margin-bottom: 0px !important;
}
.dropdown{
  display: none;
}
.custom-division{
  margin-top: 25px;
}
.sidebar{
  width: 25%;
  float: left;
  border-top: solid 1px #ddd;
  border-right: solid 1px #ddd;
  border-left: solid 1px #ddd;
  border-top-right-radius: 2px;
  border-top-left-radius: 2px;
  min-height: 40px;
  overflow-y: hidden;
}
.sidebar .header{
  height: 40px;
  float: left;
  width: 100%;
  line-height: 40px;
  padding-left: 10px;
  border-bottom: solid 1px #ddd;
  background: #eee;
}
.sidebar .item{
  height: 40px;
  float: left;
  width: 100%;
  line-height: 40px;
  padding-left: 10px;
  border-bottom: solid 1px #ddd;
}
.item:hover{
  cursor: pointer;
  border-bottom: solid 2px #1caceb !important;
}
.make-active{
  border-bottom: solid 2px #1caceb !important;
}
.content{
  width: 75%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}
@media screen and (max-width: 992px){
  .sidebar{
    display: none;
  }
  .content{
    width: 100%;
  }
  .dropdown{
    display: block;
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
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      menu: [
        {title: 'Profile', flag: true},
        {title: 'Account', flag: false},
        {title: 'Billing Information', flag: false}
      ],
      title: 'Show Menu',
      prevIndex: null,
      activeType: 'profile'
    }
  },
  components: {
    'profile': require('modules/account/Profile.vue'),
    'account': require('modules/account/Account.vue'),
    'billing': require('modules/account/Billing.vue')
  },
  methods: {
    redirect(path){
      ROUTER.push(path)
    },
    makeActive(index){
      this.title = this.menu[index].title
      if(this.prevIndex === null){
        this.prevIndex = index
        this.menu[0].flag = false
        this.menu[this.prevIndex].flag = true
      }else{
        if(this.prevIndex !== null){
          this.menu[this.prevIndex].flag = false
          this.menu[index].flag = true
          this.prevIndex = index
        }
      }
    }
  }
}
</script>
