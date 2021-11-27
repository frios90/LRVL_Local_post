require('./bootstrap')
require('./toasted')

    window.Vue = require('vue')
    window.VueResource = require('vue-resource')
  

    import VModal from 'vue-js-modal' 
    Vue.use(VModal)
    Vue.use(VModal, { dialog: true })

    import VueCurrencyFilter from 'vue-currency-filter'
    Vue.use(VueCurrencyFilter,
    {
        symbol : '$',
        thousandsSeparator: '.',
        fractionCount: 0,
        fractionSeparator: '',
        symbolPosition: 'front',
        symbolSpacing: true
    })   

    import {ServerTable, ClientTable, Event} from 'vue-tables-2';
    Vue.use(ClientTable, {}, false, 'bootstrap4');

    import { rutValidator, rutFilter, rutInputDirective } from 'vue-dni';
    Vue.directive('rut', rutInputDirective);


    import VueSweetalert2 from 'vue-sweetalert2'; 
    import 'sweetalert2/dist/sweetalert2.min.css'; 
    Vue.use(VueSweetalert2);

    import 'viewerjs/dist/viewer.css'
    import Viewer from 'v-viewer'
    Vue.use(Viewer)

    import * as VueGoogleMaps from "vue2-google-maps";

    Vue.use(VueGoogleMaps, {
        load: {
            key: "AIzaSyBTvbZqlr4H3CMyOfYB3KmDIEVCNz85ClE",
        },
    });

   
    import Multiselect from 'vue-multiselect'
    Vue.component('multiselect', Multiselect)
    


    Vue.component('app', require('./master/AppComponent').default)
    Vue.component('app-menu', require('./master/MenuComponent').default) 
    Vue.component('app-nav', require('./master/NavComponent').default) 
    Vue.component('modal-list', require('./master/ModalListComponent').default)

    import router from './router' 
    import store from './store.js'        
    const app = new Vue({
        router,
        store,
        el: '#app',
        

    })