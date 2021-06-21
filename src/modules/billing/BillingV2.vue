<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">My Plan</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto">
        <new-payment-method></new-payment-method>
      </div>

      <div class="col-lg-10 mx-auto" style="margin-top: 25px;">
        <plans :paymentMethod="null" :creditCard="null"></plans>
      </div>

      <billing-history ref="billingHistory"/>
    
  </div>
</template>
<style scoped>

.customModal{
  position: fixed;
  background-color: rgba(0, 0, 0, 1);
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 100000 !important;
  opacity: 0.5;
  pointer-events: none;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  transition: all 0.3s;
  text-align: center;
  display: none;
}
.customModal .loading{
  font-size: 50px;
  margin-top: 200px;
  height: 50px;
  float: left;
  width: 100%;
  text-align: center;
  color: #00bff3;
}

.table{
  margin-top: 25px !important;
  margin-bottom: 25px !important;
}
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
      config: CONFIG
    }
  },
  components: {
    'new-payment-method': require('modules/billing/AddPaymentMethod.vue'),
    'plans': require('modules/billing/SimplePlans.vue'),
    'billing-history': require('modules/billing/History.vue')
  },
  methods: {
    redirect(route){
      ROUTER.push(route)
    },
    retrieveHistory(){
      this.$refs.billingHistory.retrieve()
    }
  }
}
</script>
