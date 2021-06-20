<template>
  <div id="cancellationPlan">
    <div class="modal" id="unableToCancel" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Unable to Cancel!</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="message">
               Sorry, that didn't work. Your current subscription will be cancelled on date here. Keep enjoying it until then.
            </div>
            <div class="message" style="margin-bottom: 25px;">
              If you aren't sure, you can choose to have a reminder sent prior to the renewal date.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="requestToCancel" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Request to Cancel</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span class="error text-danger" style="margin-top: 25px;" v-if="errorMessage !== null"><b>Opps!</b> {{errorMessage}}</span>
            <label class="label" style="line-height: 30px;">Give us feedback <b class="text-danger">*</b></label>
            <textarea class="feedback" placeholder="Please write feedback here..." v-model="message">
              
            </textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="cancel()">Continue</button>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>
<style>
.message{
  width: 96%;
  float: left;
  margin-top: 25px;
  margin-left: 2%;
  margin-right: 2%;
}
.error{
  width: 96%;
  float: left;
  margin-left: 2%;
  margin-right: 2%;
  min-height: 50px;
  overflow-y: hidden;
  text-align: center;
}
.label{
  width: 96%;
  float: left;
  margin-left: 2%;
  margin-right: 2%;
}
.feedback{
  width: 96%;
  float: left;
  margin-left: 2%;
  margin-right: 2%;
  margin-bottom: 25px;
  border: solid 1px #ddd;
  max-height: 200px;
  min-height: 200px;
}
@media screen and (max-width: 992px) {
  .feedback{
    font-size: 16px !important;
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
  data(){
    return{
      user: AUTH.user,
      config: CONFIG,
      products: PRODUCTS,
      flag: true,
      message: null,
      errorMessage: null,
      successMessage: null
    }
  },
  methods: {
    redirect(route){
      ROUTER.push(route)
    },
    check(){
      if(this.flag === false){
        $('#unableToCancel').modal('show')
      }else{
        $('#requestToCancel').modal('show')
      }
    },
    cancel(){
      console.log('hello')
      if(this.message !== null && this.message !== '' && AUTH.user.paymentMethod){
        console.log(AUTH.user.paymentMethod.method)
        if(AUTH.user.paymentMethod.method === 'stripe'){
          this.stripe()
        }else if(AUTH.user.paymentMethod.method === 'paypal'){
          this.paypal()
        }
      }else{
        this.successMessage = null
        this.errorMessage = 'Please fill up all the required fields.'
      }
    },
    paypal(){
      let config = {
        paypal: OPKEYS.paypal
      }
      let parameter = {
        account_id: this.user.userID,
        message: this.message,
        config: config
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('paypal/cancel_plans', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data === true){
          $('#requestToCancel').modal('hide')
          AUTH.checkAuthentication(null)
          this.$parent.retrieve()
          this.errorMessage = null
          this.successMessage = 'Successfully Cancelled!'
        }else{
          this.errorMessage = response.error
          this.successMessage = null
        }
      })
    },
    stripe(){
      let parameter = {
        payment_keys: OPKEYS,
        message: this.message,
        account_id: this.user.userID
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('stripe/cancel_plans', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data === true){
          $('#requestToCancel').modal('hide')
          AUTH.checkAuthentication(null)
          this.$parent.retrieve()
          this.errorMessage = null
          this.successMessage = 'Successfully Cancelled!'
        }else{
          this.errorMessage = response.error
          this.successMessage = null
        }
      })
    }
  }
}
</script>
