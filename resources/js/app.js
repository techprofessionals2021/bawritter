// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */


require('./bootstrap');
require('select2/dist/js/select2');
window.moment = require('moment');
require('bootstrap-daterangepicker/daterangepicker');


// window.Vue = require('vue');
window.accounting = require('accounting-js');
// // CommonJS
window.Swal = require('sweetalert2');
window.bsCustomFileInput  = require('bs-custom-file-input');
window.croppie  = require('croppie');
window.DataTable  = require('datatables.net/js/jquery.dataTables.min.js');
require('datatables.net-bs4/js/dataTables.bootstrap4.min.js');
// require( 'datatables.net-responsive/js/dataTables.responsive.min.js');
// require( 'datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js');

window.Quill  = require('quill/dist/quill.min.js');

window.Chart  = require('chart.js/dist/Chart.min.js');


window.dateRanges = {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Last 12 Months': [moment().subtract(11, 'month').startOf('month'), moment()]
    };



// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */

// // const files = require.context('./', true, /\.vue$/i)
// // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

 window.GrowlNotification = require('./growl-notification.min');



// Vue.use(VueFileAgent);

// Vue.component('Order', require('./components/Order.vue').default);
// Vue.component('SubmitWork', require('./components/SubmitWork.vue').default);
// Vue.component('Notification', require('./components/Notification.vue').default);

// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */

// const app = new Vue({
//     el: '#app',
// });


// require('./custom-script');
import { createApp, ref } from 'vue';
import MyComponent from './components/MyComponent.vue';

import Order from './components/Order.vue';

import ServiceSelection from './components/order/ServiceSelection.vue';
import OrderSummary from './components/order/OrderSummary.vue';
import Instruction from './components/order/Instruction.vue';
import SubmitWork from './components/SubmitWork.vue';
import Notification from './components/Notification.vue';
import Register from './components/Register.vue';

// import VueFileAgent from 'vue-file-agent';
// import VueFileAgentStyles from 'vue-file-agent/dist/vue-file-agent.css';
import VueFileAgentNext from '@boindil/vue-file-agent-next'

import '@boindil/vue-file-agent-next/dist/vue-file-agent-next.css'


// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
})

const app  = createApp({
    components: {
    MyComponent,
    Order,
    ServiceSelection,
    OrderSummary,
    Instruction,
    SubmitWork,
    Register,
    Notification,

  },
});
// app.component('Order', order);
app.use(vuetify);
app.use(VueFileAgentNext);
app.mount('#app');
