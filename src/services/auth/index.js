// src/auth/index.js
import {router} from '../../router/index'
import ROUTER from '../../router'
import Vue from 'vue'
export default {
  user: {
    userID: 0,
    username: '',
    email: null,
    type: null,
    profile: null,
    status: null,
    plan: null,
    paymentStatus: null,
    canceledOn: null,
    enabledLesson: null,
    pausedFlag: null,
    pausedOn: null,
    paymentMethod: null
  },
  tokenData: {
    token: null,
    tokenTimer: false,
    verifyingToken: false
  },
  currentPath: false,
  setUser(userID, username, email, type, status, plan, paymentStatus, profile, canceledOn, enabledLesson, pausedFlag, pausedOn){
    if(userID === null){
      username = null
      type = null
      status = null
      plan = null
      profile = null
      email = null
      paymentStatus = null
      canceledOn = null
      enabledLesson = null
      pausedFlag = null
      pausedOn = null
    }
    this.user.userID = userID * 1
    this.user.username = username
    this.user.type = type
    this.user.email = email
    this.user.status = status
    this.user.profile = profile
    this.user.plan = plan
    this.user.paymentStatus = paymentStatus
    this.user.canceledOn = canceledOn
    this.user.enabledLesson = enabledLesson
    this.user.pausedFlag = pausedFlag
    this.user.pausedOn = pausedOn
    localStorage.setItem('account_id', this.user.userID)
    localStorage.setItem('account_type', type)
  },
  setToken(token){
    this.tokenData.token = token
    localStorage.setItem('usertoken', token)
    if(token){
      setTimeout(() => {
        let vue = new Vue()
        vue.APIRequest('authenticate/refresh', {}, (response) => {
          this.setToken(response['token'])
        }, (response) => {
          this.deaunthenticate()
        })
      }, 1000 * 60 * 60) // 60min
    }
  },
  authenticate(username, password, callback, errorCallback){
    let vue = new Vue()
    let credentials = {
      username: username,
      password: password
    }
    vue.APIRequest('authenticate', credentials, (response) => {
      this.setToken(response.token)
      vue.APIRequest('authenticate/user', {}, (userInfo) => {
        let parameter = {
          'condition': [{
            'value': userInfo.id,
            'clause': '=',
            'column': 'id'
          }]
        }
        vue.APIRequest('accounts/retrieve', parameter).then(response => {
          this.setUser(userInfo.id, userInfo.username, userInfo.email, userInfo.account_type, userInfo.status, userInfo.plan, userInfo.payment_status, response.data[0].profile, response.data[0].canceled_on_human, response.data[0].enabled_lesson, response.data[0].pause_flag, response.data[0].paused_on_human)
          ROUTER.push('/dashboard')
        })
        if(callback){
          callback(userInfo)
        }
      })
    }, (response, status) => {
      if(errorCallback){
        errorCallback(response, status)
      }
    })
  },
  checkAuthentication(callback){
    this.tokenData.verifyingToken = true
    let token = localStorage.getItem('usertoken')
    if(token){
      this.setToken(token)
      let vue = new Vue()
      vue.APIRequest('authenticate/user', {}, (userInfo) => {
        let parameter = {
          'condition': [{
            'value': userInfo.id,
            'clause': '=',
            'column': 'id'
          }]
        }
        vue.APIRequest('accounts/retrieve', parameter).then(response => {
          this.setUser(userInfo.id, userInfo.username, userInfo.email, userInfo.account_type, userInfo.status, userInfo.plan, userInfo.payment_status, response.data[0].profile, response.data[0].canceled_on_human, response.data[0].enabled_lesson, response.data[0].pause_flag, response.data[0].paused_on_human)
        }).done(response => {
          this.tokenData.verifyingToken = false
          // let location = window.location.href
          // if(this.currentPath){
          //   ROUTER.push(this.currentPath)
          // }else{
          //   window.location.href = location
          // }
        })
      }, (response) => {
        this.setToken(null)
        this.tokenData.verifyingToken = false
        this.deaunthenticate()
        // ROUTER.push(this.currentPath)
        // window.location.href = location
      })
      return true
    }else{
      this.tokenData.verifyingToken = false
      this.setUser(null)
      return false
    }
  },
  deaunthenticate(){
    localStorage.removeItem('usertoken')
    localStorage.removeItem('account_id')
    window.localStorage.clear()
    this.user.userID = 0
    this.user.username = null
    this.user.type = null
    this.user.email = null
    this.user.status = null
    this.user.profile = null
    this.user.plan = null
    this.user.paymentStatus = null
    this.user.canceledOn = null
    this.user.enabledLesson = null
    let vue = new Vue()
    vue.APIRequest('authenticate/invalidate')
    this.tokenData.token = null
    ROUTER.push('/login')
  },
  redirect(){
    if(this.tokenData.token === null){
      ROUTER.push('/login')
    }else if(this.user.type !== 'ADMIN'){
      ROUTER.push('/dashboard')
    }
  },
  redirectToLogin(){
    if(this.tokenData.token === null && (this.user.userID <= 0 || this.user.userID === null)){
      let token = localStorage.getItem('usertoken')
      if(token){
        this.checkAuthentication()
      }else{
        this.deaunthenticate()
      }
    }
  },
  validateEmail(email){
    let reg = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/
    if(reg.test(email) === false){
      return false
    }else{
      return true
    }
  }
}
