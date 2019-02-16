
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

import './bootstrap'
import Vue from 'vue'
//ルーティングの定義をインポートする
import router from './router'
import store from './store' // ★　追加
//ルートコンポーネントをインポートする
import App from './App.vue'

const createApp = async() =>{
    await store.dispatch('auth/currentUser')
}

new Vue({
    el: '#app',
    router,//ルーティングの定義を読み込む
    store, // ★　追加
    components: { App },//ルートコンポーネントの使用を宣言する
    template: '<App />',//ルートコンポーネントを描画する
});

createApp()