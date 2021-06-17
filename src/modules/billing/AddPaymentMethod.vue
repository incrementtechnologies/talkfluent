<template>
  <div class="holder">
    <div class="left-content-holder">
      <div class="accounts-item" style="margin-top: 25px;">

        <div class="new-payment-method">

          <b style="line-height: 50px;">Payment Methods</b>
          <div class="payment-methods" :class="{'active': selectedMethod && selectedMethod.title === item.title}" v-for="(item, index) in data" :key="index" @click="selectedMethod = item">
            <span class="payment-item" v-if="item.method === 'stripe' && item.details">
              <i class="fa fa-circle" ></i>
              <b style="padding-left: 10px;">****{{item.details.last4}}</b>
              <span class="pull-right" style="padding-top: 9px;" v-if="item.details">
                <i :class="'fab fa-cc-' + item.details.brand.toLowerCase()" style="padding-left: 5px;" v-if="item.method === 'stripe'"></i>
              </span>

            </span>

            <span class="payment-item" v-if="item.method === 'paypal' && item.details">
              <i class="fa fa-circle" ></i>
              <b style="padding-left: 10px;">{{item.method}}</b>
              <span class="pull-right" style="padding-top: 9px;">
                <i :class="'fab paypal'" style="padding-left: 5px;"></i>
              </span>
            </span>

            <p class="description">
              This is a test
            </p>
          </div>
        </div>


        <div class="new-payment-method" style="margin-top: 50px;">
          <div class="payment-methods" :class="{'active': selectedMethod && selectedMethod.title === item.title}" v-for="(item, index) in paymentMethods" :key="index" @click="selectedMethod = item">
            <span class="payment-item">
              <i class="fa fa-circle" :class="{'icon-active': selectedMethod && selectedMethod.title === item.title}"></i>
              <b style="padding-left: 10px;">{{item.title}}</b>
              <span class="pull-right" style="padding-top: 9px;">
                <i v-for="(iItem, iIndex) in item.icons" :class="iItem" :key="iIndex" style="padding-left: 5px;"></i>
              </span>
            </span>
            <p class="description">
              {{item.description}}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="right-content-holder">
      <checkout :paymentMethod="selectedMethod" :plan="plan"/>
    </div>
  </div>
</template>
<style scoped>
.holder{
  width: 100%;
  float: left;
}

.left-content-holder{
  width: 65%;
  float: left;
}

.right-content-holder{
  width: 30%;
  float: left;
  margin-left: 5%;
}

.new-payment-method{
  width: 49%;
  float: left;
  margin-right: 1%;
}

.fa-circle{
  font-size: 12px !important;
}

.fab{
  font-size: 32px;
}

.payment-methods{
  float: left;
  width: 100%;
  cursor: pointer;
  padding:  20px;
}

.payment-methods:hover{
  background-color: #eee;
}

.active{
  background-color: #eee;
}

.icon-active{
  color: #39b54a;
}

.payment-item{
  float: left;
  width: 100%;
  line-height: 50px;
  cursor: pointer;
}

.description{
  color:  #999;
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
  mounted(){
    this.retrieve()
  },
  data(){
    return{
      user: AUTH.user,
      config: CONFIG,
      products: PRODUCTS,
      errorMessage: null,
      newPaymentMethod: null,
      data: [],
      paymentMethods: [{
        title: 'Credit Cards(Stripe)',
        description: 'Authorized billing payment via visa, mastercard, discover',
        icons: [
          'fab fa-cc-visa',
          'fab fa-cc-mastercard',
          'fab fa-cc-discover'
        ],
        type: 'stripe'
      }, {
        title: 'PayPal',
        description: 'Authorized billing payment via PayPal',
        icons: [
          'fab fa-paypal'
        ],
        type: 'paypal'
      }],
      selectedMethod: null,
      plan: {
        amount: 49.00,
        currency: '$',
        billed: 'month',
        subLabel: 'USD per month',
        description: 'SUBSCRIPTION PLANS THAT WORK FOR EVERYONE',
        features: [
          'Unlimited access to hundreds of real-world, native Spanish conversation scripts',
          'Perfect your pronunciation with accurate audio translations spoken by natives.',
          '60-day risk-free, money-back guarantee'
        ]
      }
    }
  },
  components: {
    'checkout': require('modules/billing/Checkout.vue'),
    'active-payments': require('modules/billing/ActivePayments.vue')
  },
  methods: {
    redirect(route){
      ROUTER.push(route)
    },
    retrieve(){
      let parameter = {
        'condition': [{
          column: 'account_id',
          value: this.user.userID,
          clause: '='
        }]
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('payment_methods/retrieve', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = []
        }
      })
    }

  }
}
</script>
