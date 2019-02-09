import Vue from 'vue'
import VueRouter from 'vue-router'

//ページコンポーネントをインポートする
import PhotoList from './components/PhotoList.vue'
import Login from './components/Login.vue'

//VueRouterプラグインを使用する
//これにより<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

//パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: PhotoList
    },
    {
        path: '/login',
        component: Login
    },
]

//VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',//historyモードを追加
    routes
})

//VueRouterインスタンスをエクスポートする
//app.jsでインポートするため
export default router