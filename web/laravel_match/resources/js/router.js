import Vue from 'vue'
import VueRouter from 'vue-router'

import store from './store' // ナビゲーションガードを追加するためにauthストアのcheckゲッターを使用する

// ページコンポーネントをインポートする
import Top from './pages/Top.vue'
import Mypage from './pages/Mypage.vue'
import ProjectList from './pages/ProjectList.vue'
import RegisterProject from './pages/RegisterProject.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Edit from './pages/Edit.vue'
import PassResetEmail from './pages/PassResetEmail.vue'
import PassReset from './pages/PassReset.vue'

import System from './pages/errors/System.vue'


// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: Top
    },
    {
        path: '/project-list',
        component: ProjectList
    },
    {
        path: '/register-project',
        component: RegisterProject
    },
    {
        path: '/register',
        component: Register,
        beforeEnter (to, from, next) { // ログイン状態でアクセスがあったらマイページへ遷移する
            if (store.getters['auth/check']) {
                next('/mypage')
            } else {
                next()
            }
        }
    },
    {
        path: '/users/:userId/edit',
        // path: '/users/edit',
        name: 'edit',
        component: Edit,
        beforeEnter (to, from, next) { // 未ログイン状態でアクセスがあったらログインページへ遷移する
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/login')
            }
        }
    },
    {
        path: '/login',
        component: Login,
        beforeEnter (to, from, next) { // ログイン状態でアクセスがあったらマイページへ遷移する
            if (store.getters['auth/check']) {
                next('/mypage')
            } else {
                next()
            }
        }
    },
    {
        path: '/password/email',
        component: PassResetEmail,
        beforeEnter (to, from, next) { // ログイン状態でアクセスがあったらマイページへ遷移する
            if (store.getters['auth/check']) {
                next('/mypage')
            } else {
                next()
            }
        }
    },
    {
        path: '/password/reset/*',
        component: PassReset,
        beforeEnter (to, from, next) { // ログイン状態でアクセスがあったらマイページへ遷移する
            if (store.getters['auth/check']) {
                next('/mypage')
            } else {
                next()
            }
        }
    },
    {
        path: '/mypage',
        component: Mypage,
        beforeEnter (to, from, next) { // 未ログイン状態でアクセスがあったらログインページへ遷移する
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/login')
            }
        }
    },
    {
        path: '/500',
        component: System
    }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
