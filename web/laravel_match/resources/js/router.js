import Vue from 'vue'
import VueRouter from 'vue-router'

import store from './store' // ナビゲーションガードを追加するためにauthストアのcheckゲッターを使用する

// ページコンポーネントをインポートする
import Top from './pages/Top.vue'
import Mypage from './pages/Mypage.vue'
import Projects from './pages/Projects.vue'
import ProjectsRegister from './pages/ProjectsRegister.vue'
import ProjectsEdit from './pages/ProjectsEdit.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Edit from './pages/Edit.vue'
import PassResetEmail from './pages/PassResetEmail.vue'
import PassReset from './pages/PassReset.vue'
import ProjectDetail from './pages/ProjectDetail.vue'
import PublicMessages from './pages/PublicMessages.vue'
import PrivateMessages from './pages/PrivateMessages.vue'
import PrivateMessagesDetail from './pages/PrivateMessagesDetail.vue'

// エラーページコンポーネントをインポートする
import NotFound from './pages/errors/NotFound.vue'
import Forbidden from './pages/errors/Forbidden.vue'
import Unauthorized from './pages/errors/Unauthorized.vue'
import UnauthorizedCSRF from './pages/errors/UnauthorizedCSRF.vue'
import System from './pages/errors/System.vue'
import Unexpected from './pages/errors/Unexpected.vue'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: Top,
        beforeEnter (to, from, next) { // ログイン状態でアクセスがあったらマイページへ遷移する
            if (store.getters['auth/check']) {
                next('/mypage')
            } else {
                next()
            }
        }
    },
    {
        path: '/projects/list',
        name: 'projectsList',
        component: Projects,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
        }
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
        path: '/users/:id/edit',
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
        name: 'mypage',
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
        path: '/projects/register',
        component: ProjectsRegister,
        beforeEnter (to, from, next) { // 未ログイン状態でアクセスがあったらログインページへ遷移する
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/login')
            }
        }
    },
    {
        path: '/projects/:id/edit',
        name: 'projectsEdit',
        component: ProjectsEdit,
        beforeEnter (to, from, next) { // 未ログイン状態でアクセスがあったらログインページへ遷移する
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/login')
            }
        }
    },
    {
        path: '/project/detail/:id',
        name: 'projectDetail',
        component: ProjectDetail,
        props: true,
        beforeEnter (to, from, next) { // 未ログイン状態でアクセスがあったらログインページへ遷移する
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/login')
            }
        }
    },
    {
        path: '/public_messages/list',
        component: PublicMessages,
        beforeEnter (to, from, next) { // 未ログイン状態でアクセスがあったらログインページへ遷移する
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/login')
            }
        }
    },
    {
        path: '/private_messages/list',
        component: PrivateMessages,
        beforeEnter (to, from, next) { // 未ログイン状態でアクセスがあったらログインページへ遷移する
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/login')
            }
        }
    },
    {
        path: '/private_messages/detail/:id',
        name: 'privateMessagesDetail',
        component: PrivateMessagesDetail,
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
    },
    {
        path: '/403',
        component: Forbidden
    },
    {
        path: '/401',
        component: Unauthorized
    },
    {
        path: '/419',
        component: UnauthorizedCSRF
    },
    {
        path: '/unexpected-error',
        component: Unexpected
    },
    {
        path: '*',
        component: NotFound
    }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    scrollBehavior () {
        return { x: 0, y: 0 }
    },
    routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
