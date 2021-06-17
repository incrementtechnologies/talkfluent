<template>
    <div>
      <div class="payment-accounts">
        <div class="text-danger" v-if="errorMessage !== null" style="padding-top: 10px; padding-bottom: 10px;">Opps! {{errorMessage}}</div>
        <div :class="{complete}">
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
    <button class="btn btn-primary btn-block" v-on:click="addNewPaymentMethod()">
      Authorize
    </button>
    </div>
  </div>
</template>
<style>
.btn-block{
  height: 50px !important;
}
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
.payment-methods{
  float: left;
  width: 100%;
}
.payment-label{
  line-height: 100px;
  border-bottom: solid 1px #eee;
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
    CardExpiry
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
</script>
