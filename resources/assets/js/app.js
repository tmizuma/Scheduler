
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'
import VueRouter from 'vue-router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/ja'

require('./bootstrap');
Vue.use(VueRouter);
Vue.use(ElementUI, {locale});
window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes: [
        {path: '/', component: require('./components/SchedulerListComponent.vue')},
        {path: '/:day', component: require('./components/SchedulerListComponent.vue')},
        {path: '/scheduler/new', component: require('./components/NewSchedulerComponent.vue')},
        {path: '/scheduler/edit/:id', component: require('./components/UpdateSchedulerComponent.vue')}
    ]
});

const app = new Vue({
    router
}).$mount('#app');
