import './bootstrap' // Axiosライブラリの設定を読み込む
import Vue from 'vue'
import router from './router' // ルーティングの定義をインポートする
import titleMixin from './title' // タイトルをインポートする
import store from './store' // Vuexストアをインポートする
import App from './App.vue' // ルートコンポーネントをインポートする
// import 'core-js/stable'
// import 'regenerator-runtime/runtime'

const createApp = async function () {
    await store.dispatch('auth/currentUser')

    Vue.mixin(titleMixin)

    // currentUserアクションの非同期処理が終わってからVueインスタンスを生成する
    // インスタンス生成前にログインチェックをおこなっている
    new Vue({
        el: '#app',
        router: router, // ルーティングの定義を読み込む
        store: store, // ストアの使用を宣言する
        components: { App }, // ルートコンポーネントの使用を宣言する
        template: '<App />' // ルートコンポーネントを描画する
    })
}

createApp()
