import Vue from 'vue'
import Vuex from 'vuex'
import smoothScroll from 'vue-smoothscroll' // ページ内移動のためのパッケージ

import auth from './auth'
import error from './error'
import message from './message'

Vue.use(Vuex)
Vue.use(smoothScroll)

const store = new Vuex.Store({
    modules: {
        auth,
        error,
        message
    }
})

export default store
