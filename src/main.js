import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import VueHead from 'vue-head'
import './scss/styles.scss'
import * as bootstrap from 'bootstrap'
import topnav from "./components/nav.vue";
import footNav from "./components/footer.vue";
window.bootstrap = bootstrap;


const app = createApp(App)
app.component('topnav', topnav)
app.component('footNav', footNav)
app.use(router, VueHead, bootstrap)

//app.component()
app.mixin({
    methods: {
        toast(msg = 'hello toast') {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl)
            })
            toastList.forEach(toast => toast.show())
        },
        rightnav() {
            const rn = document.getElementById('rnav');
            rn.style = "display:block";
        },
        crmenu() {
            const rn = document.getElementById('rnav');
            rn.style = "display:none";
        }
    },
})
var token = localStorage.getItem('token') ? localStorage.getItem('token') : '0';
app.config.globalProperties.$token = token;
app.config.globalProperties.$api = 'http://aifx.local/api/?token=' + token + '&';
app.config.globalProperties.$imagethmb = 'http://aifx.local/imgs/thumb/';
app.config.globalProperties.$image = 'http://aifx.local/imgs/';
app.mount('#app')
