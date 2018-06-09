
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('ble-viewer', require('./components/BLEViewer.vue'));

const app = new Vue({
    el: '#app'
});


/**
 * font-awesome integration read the following article
 * https://stackoverflow.com/questions/49304017/install-font-awesome-5-with-npm-for-scss-usage/49328677#49328677
 */
// import fontawesome from '@fortawesome/fontawesome'
// import regular from '@fortawesome/fontawesome-free-regular'
// import solid from '@fortawesome/fontawesome-free-solid'
// import brands from '@fortawesome/fontawesome-free-brands'
//
// fontawesome.library.add(regular)
// fontawesome.library.add(solid)
// fontawesome.library.add(brands)
