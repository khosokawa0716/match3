import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
    project: null,
    apiStatus: null,
    registerErrorMessages: null,
    updateErrorMessages: null
}

const getters = {
    // projectのstateが存在する場合には名前などの値を返す。ない場合に呼ばれたら空文字を返す
    projectid: state => state.project ? state.project.id : '',
    title: state => state.project ? state.project.title : ''
}

const mutations = {
    setProject (state, project) {
        state.project = project
    },
    setApiStatus (state, status) {
        state.apiStatus = status
    },
    setRegisterErrorMessages (state, messages) {
        state.registerErrorMessages = messages
    },
    setUpdateErrorMessages (state, messages) {
        state.updateErrorMessages = messages
    }
}

const actions = { // それぞれのアクションは、非同期処理の結果によって後続の処理を分岐させる
    // 案件登録
    async register (context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/projects/register', data)

        if (response.status === CREATED) {
            context.commit('setApiStatus', true)
            context.commit('setProject', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setRegisterErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    // 案件更新
    async update (context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.put('/projects/' + data.get('id'), data)

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setProject', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setUpdateErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
