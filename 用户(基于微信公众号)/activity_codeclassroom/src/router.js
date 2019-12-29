import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import Pay from './views/pay.vue'
import Pay_success from './views/pay_success.vue'
import All_course from './views/all_course.vue'
import Course_details from './views/course_details.vue'
import Page_view from './views/page_view.vue'
import Jiangli from './views/jiangli.vue'
import Geren from './views/geren.vue'
import Jifenshangc from './views/jifenshangc.vue'
import Duihuanjl from './views/duihuanjl.vue'
import Youjiangrw from './views/youjiangrw.vue'
import Gerenxinx from './views/gerenxinx.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
		{
		  path: '/pay',
		  name: 'pay',
		  component: Pay
		},
		{
		  path: '/pay_success',
		  name: 'pay_success',
		  component: Pay_success
		},
		{
		  path: '/all_course',
		  name: 'all_course',
		  component: All_course
		},
		{
		  path: '/course_details',
		  name: 'course_details',
		  component: Course_details
		},
		{
		  path: '/page_view',
		  name: 'page_view',
		  component: Page_view
		},
		{
		  path: '/jiangli',
		  name: 'jiangli',
		  component: Jiangli
		},
		{
		  path: '/geren',
		  name: 'geren',
		  component: Geren
		},
		{
		  path: '/jifenshangc',
		  name: 'jifenshangc',
		  component: Jifenshangc
		},
		{
		  path: '/duihuanjl',
		  name: 'duihuanjl',
		  component: Duihuanjl
		},
		{
		  path: '/youjiangrw',
		  name: 'youjiangrw',
		  component: Youjiangrw
		},
		{
		  path: '/gerenxinx',
		  name: 'gerenxinx',
		  component: Gerenxinx
		},
  ]
})
