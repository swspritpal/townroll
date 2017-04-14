
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

import Vue from 'vue';
import VeeValidate from 'vee-validate';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('../components/frontend/Example.vue'));
Vue.component('like', require('../components/frontend/like.vue'));



/*const app = new Vue({
    el: '#app'
});

Vue.use(VeeValidate);*/

/*new Vue({
    el: '#signup_form',
    data: {
        hello: 'Hello Vue.js!'
    }
})
*/

window.toastr = require('toastr/build/toastr.min.js');
window.innerHeight = 800;

window.toastr.options = {
    positionClass: "toast-bottom-right",
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
    //closeButton: true
};
