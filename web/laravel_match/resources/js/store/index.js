import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import project from './project'
import error from './error'

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        auth,
        project,
        error
    }
})

export default store
