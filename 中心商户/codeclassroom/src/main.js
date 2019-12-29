// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

Vue.config.productionTip = false
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
// import '/static/css/table.css'

import 'vue-croppa/dist/vue-croppa.css'

import echarts from 'echarts'
Vue.prototype.$echarts = echarts 

import Croppa from 'vue-croppa'
import '../static/css/font-awesome.min.css'

import VueCookies from 'vue-cookies'
Vue.use(VueCookies)
Vue.use(Croppa)
Vue.use(ElementUI);
import preview from 'vue-photo-preview'
import 'vue-photo-preview/dist/skin.css'
Vue.use(preview)

import iView from 'iview';
import 'iview/dist/styles/iview.css';
Vue.use(iView);

router.beforeEach((to,from,next) => {
    if(to.matched.some( m => m.meta.auth)){
// 对路由进行验证
        if($.cookie("token") == null || $.cookie("token") == undefined || $.cookie('token') == 'null') {
            next({path:'/login'})
        }else{
            next()
        }
    }else{
        next()
    }
})
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
