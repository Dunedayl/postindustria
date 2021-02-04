import '@babel/polyfill'
import 'mutationobserver-shim'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './axios'


window.$ = window.jQuery = require('jquery');

createApp(App).use(store).use(router).mount('#app')
