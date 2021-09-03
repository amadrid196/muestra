/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');

 window.Vue = require('vue').default;

 /**
  * The following block of code may be used to automatically register your
  * Vue components. It will recursively scan this directory for the Vue
  * components and automatically register them with their "basename".
  *
  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
  */

 // const files = require.context('./', true, /\.vue$/i)
 // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

 Vue.component('menu-user', require('./components/ExampleComponent.vue').default);

 import router from './route'
 import vSelect from "vue-select";
 import Toasted from 'vue-toasted';
 import Vue from 'vue';
 import VueEllipseProgress from 'vue-ellipse-progress';
 import VuePlugin from 'dom-pdf';
 import Multiselect from 'vue-multiselect';
 /**
  * Next, we will create a fresh Vue application instance and attach it to
  * the page. Then, you may begin adding components to this application
  * or customize the JavaScript scaffolding to fit your unique needs.
  */
  Vue.use(VueEllipseProgress);
  Vue.component("v-select", vSelect);
  Vue.use(Toasted);
  Vue.use(VuePlugin);
  Vue.component('multiselect', Multiselect)

 const app = new Vue({
     el: '#app',
     router,
     data: {
        menu: 0,
        nav1: 0,
        nav2: 0,
        nav3: 0,
        nav4: 0,
     },
 });
