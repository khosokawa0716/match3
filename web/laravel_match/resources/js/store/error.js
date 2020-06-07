const state = {
    code: null
}

const mutations = {
    setCode: function (state, code) {
        state.code = code
    }
}

export default {
    namespaced: true,
    state,
    mutations
}
