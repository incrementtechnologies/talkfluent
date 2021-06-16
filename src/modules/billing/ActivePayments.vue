<template>
  <div class="payment-accounts">

      <b>Payment Accounts</b>
    
      <span class="accounts-item" v-for="item, index in creditCard" v-if="paymentMethod !== null && creditCard !== null">
        <span class="details">
          <i v-bind:class="{'fas fa-circle': item.id === parseInt(paymentMethod.source), 'far fa-dot-circle': item.id !== paymentMethod.source}" class="text-primary" v-on:click="selectPaymentAccount(index)"></i><i v-bind:class="item.brand_icon" class="fab"></i> ********{{item.last4 + ' - ' + item.status}}
        </span>
      </span>
    

      <span class="accounts-item" v-for="item, index in paypal" v-if="paymentMethod !== null && paypal !== null">
        <span class="details">
          <i class="text-primary" v-bind:class="{'fas fa-circle': item.id === parseInt(paymentMethod.source), 'far fa-dot-circle': item.id !== paymentMethod.source}" v-on:click="selectPaymentAccount(index)"></i><i class="fab fa-paypal"></i> {{item.name}}
        </span>
      </span>
    
  </div>
</template>
<style scoped>

.payment-accounts{
  width: 100%;
  float: left;
}

.payment-accounts .accounts-item{
  width: 100%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  display: table;
}


</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
import OPKEYS from '../../payment.js'
export default {
  data(){
    return{
      user: AUTH.user,
      config: CONFIG
    }
  },
  methods: {
  }
}
</script>
