<template>
  <div class="holder">
    <b>Current subscription plan</b>
    <br>
    <div class="plan-holder">
      <span class="plan-header">
        <p>
          <b>Monthly Plan for $99.00</b>
        </p>
        <p style="color: #999;">
          Next billing is on: July 31, 2021
        </p>
      </span>
      <span class="plan-footer">
        <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" aria-label="Close">Change Plan</button>
      </span>
    </div>
  </div>
</template>
<style scoped>

.plan-holder{
  width: 32%;
  padding: 20px;
  border-radius: 5px;
  border: solid 1px #eee;
  margin-top: 25px;
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
  },
  data(){
    return{
      user: AUTH.user,
      config: CONFIG,
      products: PRODUCTS
    }
  },
  props: ['paymentMethod', 'creditCard'],
  components: {
  },
  methods: {
    redirect(route){
      ROUTER.push(route)
    }
  }
}
</script>
