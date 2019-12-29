import Vue from 'vue'
import Router from 'vue-router'
import miss from '@/pages/miss'
import login from '@/pages/login'
import main from '@/pages/main'


//新活动模块
import xindex from '@/pages/xinhuodong/xindex'
import xtongji from '@/pages/xinhuodong/xtongji'
import xyuyue from '@/pages/xinhuodong/xyuyue'

//测评e
Vue.use(Router)

export default new Router({
    // mode: 'history',
    routes: [
        {
            path: '*',
            component: miss,
        },
        {
            path: '/',
            name: 'login',
            component: login,
        },
        {
            path: '/main',
            name: 'main',
            component: main
        },
        {
            path: '/xindex',
            name: 'xindex',
            component: xindex,
        },
        {
            path: '/xtongji',
            name: 'xtongji',
            component: xtongji,
        },
        {
            path: '/xyuyue',
            name: 'xyuyue',
            component: xyuyue,
        },
        {
            path: '/login',
            name: 'login',
            component: login,
        },
    ]


})
