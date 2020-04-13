import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Top from './pages/Top.vue'
import Mypage from './pages/Mypage.vue'
import ProjectList from './pages/ProjectList.vue'
import RegisterProject from './pages/RegisterProject.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import PassResetEmail from './pages/PassResetEmail.vue'
import PassReset from './pages/PassReset.vue'

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
        path: '/login',
        component: Login
    },
    {
        path: '/password/email',
        component: PassResetEmail,
        // beforeEnter (to, from, next) {
        //     if (this.$store.getters['auth/check']) {
        //         next('/mypage')
        //     } else {
        //         next()
        //     }
        // }
    },
    {
        path: '/password/reset/*',
        component: PassReset,
        // beforeEnter (to, from, next) {
        //     if (this.$store.getters['auth/check']) {
        //         next('/mypage')
        //     } else {
        //         next()
        //     }
        // }
    },
    {
        path: '/register',
        component: Register
    },
    {
        path: '/mypage',
        component: Mypage,
        // beforeEnter (to, from, next) {
        //     // ログインしていない状態でマイページのリクエストがあったら、ログインページに移動する
        //     if (this.$store.getters['auth/check']) {
        //         next('')
        //     } else {
        //         next('/login')
        //     }
        // }
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
