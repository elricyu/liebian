import Vue from 'vue'
import App from './App.vue'
import router from './router'

import 'normalize.css/normalize.css'
import 'lib-flexible'

import jquery from 'jquery'
window.jquery = window.$ = jquery

import "bootstrap"
import "bootstrap/dist/css/bootstrap.css";
import "font-awesome/css/font-awesome.min.css"

import axios from 'axios'
window.axios = axios
import qs from 'qs'
window.qs = qs

import wx from 'weixin-js-sdk'
window.wx = wx
//微信分享
import wechat from 'wechat.js'
window.wechat = wechat 

// 获取浏览记录
import record from 'record.js'
window.record = record

import Vant from 'vant';
import 'vant/lib/index.css';
Vue.use(Vant);

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
