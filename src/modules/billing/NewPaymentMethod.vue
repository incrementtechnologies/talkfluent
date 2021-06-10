<template>
  <div>
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
    
      <span class="accounts-item" style="margin-top: 25px;">
        <button class="btn btn-primary" v-if="addPaymentMethodFlag === false && user.paymentStatus === 'failed'" v-on:click="setPaymentMethodFlag(true)">Add Payment Method</button>
        <div class="new-payment-method mb-2" v-if="addPaymentMethodFlag === true">
          <div class="radio">
            <label><input type="radio" name="payment_method" v-model="newPaymentMethod" value="credit_card"> Credit Card</label>
            <div class="text-danger" v-if="errorMessage !== null" style="padding-top: 10px; padding-bottom: 10px;">Opps! {{errorMessage}}</div>
            <div :class="{complete}" v-if="newPaymentMethod === 'credit_card'" >
              <div class="row">
                <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                  <label for="address">Card Number</label>
                  <card-number class="stripe-element card-number"
                    ref="cardNumber"
                    :stripe="stripeSK"
                    @change="number = $event.complete"
                    :options="options"
                  />
                </div>
              </div>

              <div class="row">
                <div class="form-group login-spacer col-lg-6 col-md-6 col-sm-12">
                  <label for="address">Expiration</label>
                  <card-expiry class="stripe-element card-expiry"
                    ref="cardExpiry" 
                    :stripe="stripeSK" 
                    @change="expiry = $event.complete"
                    :options="options"
                  />
                </div>

                <div class="form-group login-spacer col-lg-6 col-md-6 col-sm-12">
                  <label for="address">CVC</label>
                  <div id="signup-card-number">
                    <card-cvc class='stripe-element card-cvc'
                      ref='cardCvc'
                      :stripe="stripeSK" 
                      @change="expiry = $event.complete" 
                      :options="options"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="radio">
            <label><input type="radio" name="payment_method" v-model="newPaymentMethod" value="paypal"> PayPal</label>
<!--                 <div class="row" v-if="newPaymentMethod === 'paypal'">
              <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                <label for="nickname">Nickname</label>
                <input type="text" class="form-control form-control-login" id="nickname" placeholder="Enter nickname(Optional)" v-model="newPaypal.nickname">
              </div>
            </div> -->
          </div>
          <button class="btn btn-primary" v-on:click="addNewPaymentMethod()" v-if="newPaymentMethod === 'credit_card'">
            Authorize
          </button>
        </div>
      </span>
    </div>
    <create-paypal-plans v-if="newPaymentMethod === 'paypal' && addPaymentMethodFlag"></create-paypal-plans>
  </div>
</template>
<style>

.payment-accounts, .billing-summary{
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

.new-payment-method{
  width: 50%;
  float: left;
}

.accounts-item .details{
  margin-right: 10px;
  margin-top: 10px;
  width: 100%;
  float: left;
}

.accounts-item i{
  font-size: 32px;
  display:inline-block;
  vertical-align: -6px;
  margin-right: 10px;
}

.accounts-item i:hover{
  cursor: pointer;
}

.stripe-element{
  height: 45px;
  display: block;
  width: 100%;
  padding: .5rem .75rem;
  font-size: 12px;
  background-color: #fff;
  background-image: none;
  background-clip: padding-box;
  border: 1px solid rgba(0,0,0,.15);
  border-radius: .25rem;
  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.stripe-element.card-number, .stripe-element.card-expiry, .stripe-element.card-cvc{
  padding: 0px;
}

.StripeElement{
  padding: .90rem .75rem;
  color: #495057;
  line-height: 1.25;
}

@media (max-width: 992px){
  .new-payment-method{
    width: 100%;
    float: left;
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
import CardExpiry from '../../components/stripe/CardExpiry'
import CardCvc from '../../components/stripe/CardCvc'
import CardNumber from '../../components/stripe/CardNumber'
import { Stripe } from '../../components/stripe/stripeElements'
export default {
  data(){
    return{
      user: AUTH.user,
      config: CONFIG,
      products: PRODUCTS,
      errorMessage: null,
      addPaymentMethodFlag: false,
      newPaymentMethod: null,
      complete: false,
      number: false,
      expiry: false,
      cvc: false,
      stripeSK: (OPKEYS.flag === false) ? OPKEYS.stripe.dev_pk : OPKEYS.stripe.live_pk,
      options: {
        style: {base: {
          fontSize: '16px'
        }}
      }
    }
  },
  props: ['creditCard', 'paypal', 'paymentMethod'],
  components: {
    CardNumber,
    CardCvc,
    CardExpiry,
    'create-paypal-plans': require('modules/billing/PaypalPlans.vue')
  },
  methods: {
    redirect(route){
      ROUTER.push(route)
    },
    setPaymentMethodFlag(flag){
      this.addPaymentMethodFlag = flag
    },
    selectPaymentAccount(index){
      this.accountsItem[index].flag = !this.accountsItem[index].flag
    },
    addNewPaymentMethod(){
      if(this.newPaymentMethod === 'credit_card'){
        $('#loading').css({'display': 'block'})
        Stripe.createSource().then(data => {
          if(data.error !== undefined){
            $('#loading').css({'display': 'none'})
            // console.log(data.error)
            this.errorMessage = data.error.message
          }else{
            let parameter = {
              email: this.user.email,
              source: data.source,
              account_id: this.user.userID,
              payment_keys: OPKEYS
            }
            this.APIRequest('stripes/add_payment_method', parameter).then(response => {
              if(response.data === true){
                $('#loading').css({'display': 'none'})
                this.addPaymentMethodFlag = false
                this.$parent.retrieveBilling()
              }
            })
          }
        })
      }
    }
  }
}
</script>
