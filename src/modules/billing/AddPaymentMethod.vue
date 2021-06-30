<template>
  <div class="holder">
    <div class="left-content-holder">
      <div class="accounts-item">
        <b style="line-height: 50px;">Current payment method</b>
        <div class="new-payment-method">
          
          <div class="payment-methods" v-for="(item, index) in data" :key="'i' + index">
            <span class="payment-item" v-if="item.method === 'stripe' && item.details">
              <i class="fa fa-circle" ></i>
              <b style="padding-left: 10px;">****{{item.details.last4}} - {{item.status.toUpperCase()}}</b>
            </span>

            <span class="payment-item" v-if="item.method === 'paypal' && item.details">
              <i class="fa fa-circle" ></i>
              <b style="padding-left: 10px;">{{item.method.toUpperCase()}}</b>
              <span class="pull-right" style="padding-top: 9px;">
                <i :class="'fab paypal'" style="padding-left: 5px;"></i>
              </span>
            </span>

            <p style="padding-top: 9px;" v-if="item.details">
              <i :class="'fab fa-cc-' + item.details.brand.toLowerCase()" style="padding-left: 5px;" v-if="item.method === 'stripe'"></i>
              <i :class="'fab fa-paypal'" style="padding-left: 5px;" v-if="item.method === 'paypal'"></i>
            </p>

            <p class="description" v-if="item.details && item.method === 'stripe'">
              Expired on {{item.details.exp_month + '/' + item.details.exp_year}}
            </p>

            <p>
              <button class="btn btn-primary btn-block" @click="showPaymentMethodModal()">Change payment method</button>
            </p>
          </div>

          <div class="payment-methods" style="border: none;" v-if="data.length === 0">
            <span class="payment-item">
              <p class="text-danger text-center">No existing payment method.</p>
            </span>

            <p>
              <button class="btn btn-primary btn-block" @click="showPaymentMethodModal()">Add payment method</button>
            </p>
          </div>


        </div>

      </div>
    </div>

    <div class="modal fade" id="newPaymentMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">New Payment Method</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <li class="page-item" @click="selectedMethod = 'stripe'" :class="{'active': selectedMethod === 'stripe'}">
                  <i :class="'fab fa-cc-visa'" style="padding-right: 5px;"></i>
                  <i :class="'fab fa-cc-discover'" style="padding-right: 5px;"></i>
                  <i :class="'fab fa-cc-mastercard'" style="padding-right: 5px;"></i>
                  <br/>
                  <b>CC/DC</b></li>
                <li class="page-item" @click="selectedMethod = 'paypal'"  :class="{'active': selectedMethod === 'paypal'}"><i :class="'fab fa-paypal'" style="padding-right: 5px;"></i><b>PayPal</b></li>
              </ul>
            </nav>

            <div class="new-payment-methods" v-if="selectedMethod === 'stripe'">
              <stripe-cc></stripe-cc>
            </div>


            <div class="new-payment-methods" v-if="selectedMethod === 'paypal'">
              <button type="button" class="btn btn-primary btn-block" @click="authorizePayPal()">Authorize</button>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>
<style scoped>

.btn{
  height: 50px !important;
}
.holder{
  width: 100%;
  float: left;
}

.left-content-holder{
  width: 100%;
  float: left;
}

.new-payment-method{
  width: 100%;
  float: left;
}

.fa-circle{
  font-size: 12px !important;
}

.fab{
  font-size: 32px;
}

.payment-methods{
  float: left;
  width: 32%;
  margin-right: 1%;
  padding:  20px;
  border-radius: 5px;
  border: solid 1px #eee;
  min-height: 200px;
  overflow-y: hidden;
  margin-bottom: 10px;
}

.payment-item{
  display: flex;
  align-items: center;
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
}

.description{
  color:  #999;
}

.pagination{
  width: 100%;
  float: left;
}

.page-item{
  width: 50%;
  float: left;
  line-height: 60px !important;
  height: 60px;
  text-align: center;
  display: flex;
  align-items: center;
  padding-left: 20px;
  padding-right: 20px;
}

.page-item:hover{
  background-color: #eee;
  cursor: pointer;
}

.modal-body{
  padding: 15px !important;
}

@media screen and (max-width: 610px){
  .payment-methods{
    width: 96%;
    margin-left: 2%;
    margin-right: 2%;
  }
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
      selectedMethod: 'stripe',
      data: [],
      paymentMethods: [{
        title: 'Credit Cards(Stripe)',
        description: 'Authorized new billing payment method via visa, mastercard, discover',
        icons: [
          'fab fa-cc-visa',
          'fab fa-cc-mastercard',
          'fab fa-cc-discover'
        ],
        type: 'stripe'
      }, {
        title: 'PayPal',
        description: 'Authorized new billing payment method via PayPal',
        icons: [
          'fab fa-paypal'
        ],
        type: 'paypal'
      }],
      newSelectMethod: null,
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
    'active-payments': require('modules/billing/ActivePayments.vue'),
    'stripe-cc': require('modules/billing/Stripe.vue')
  },
  methods: {
    redirect(route){
      ROUTER.push(route)
    },
    retrieveHistory(){
      this.$parent.retrieveHistory()
    },
    retrieve(){
      $('#newPaymentMethod').modal('hide')
      let parameter = {
        'condition': [{
          column: 'account_id',
          value: this.user.userID,
          clause: '='
        }, {
          column: 'status',
          value: 'active',
          clause: '='
        }],
        sort: {
          created_at: 'desc'
        }
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('payment_methods/retrieve', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data.length > 0){
          this.data = response.data
          AUTH.user.paymentMethod = response.data[0]
        }else{
          this.data = []
          AUTH.user.paymentMethod = null
        }
      })
    },
    updatePaymentMethod(item, status){
      if(this.user.userID < 0){
        return
      }
      let parameter = {
        id: item.id,
        status: status,
        account_id: this.user.userID
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('payment_methods/update', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        this.retrieve()
      })
    },
    deletePaymentMethod(item){
      let parameter = {
        id: item.id
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('payment_methods/delete', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        this.retrieve()
      })
    },
    showPaymentMethodModal(){
      $('#newPaymentMethod').modal('show')
    },
    authorizePayPal(){
      if(AUTH.user.userID <= 0){
        return
      }
      if(AUTH.user.plan === null || AUTH.user.canceledOn !== null){
        return
      }
      if(AUTH.user.paymentMethod === null || (AUTH.user.paymentMethod && AUTH.user.paymentMethod.details === null)){
        return
      }
      if(AUTH.user.paymentMethod.method === 'stripe'){
        // from stripe to paypal
        console.log('create new paypal PLANS')
        this.createPaypal(AUTH.user.plan)
      }else{
        // update paypal payment
        console.log('update paypal plan')
        this.upgradePlanPaypal(AUTH.user.plan)
      }
    },
    createPaypal(plan){
      let nickname = null
      let paypal = {
        'paypal': OPKEYS.paypal
      }
      let parameter = {
        account_id: this.user.userID,
        plan: plan,
        config: paypal,
        nickname: null
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('paypal/create_on_billing', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.return_url !== null){
          window.location.href = response.return_url
        }
      })
    },
    upgradePlanPaypal(plan){
      let config = {
        'paypal': OPKEYS.paypal
      }
      let parameter = {
        account_id: this.user.userID,
        'config': config,
        payment_status: this.user.paymentStatus,
        plan: plan
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('paypal/upgrade', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data !== null){
          window.location.href = response.data
        }else{
          this.$parent.notAllowedMessage = response.error
          $('#notAllowedUpgrade').modal('show')
        }
      })
    }
  }
}
</script>
