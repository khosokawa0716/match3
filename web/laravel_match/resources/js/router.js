import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Top from './pages/Top.vue'
import Mypage from './pages/Mypage.vue'
import ProjectList from './pages/ProjectList.vue'
import RegisterProject from './pages/RegisterProject.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'

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
        path: '/register',
        component: Register
    },
    {
        path: '/mypage',
        component: Mypage
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
