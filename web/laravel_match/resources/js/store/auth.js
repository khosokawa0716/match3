import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null,
    updateErrorMessages: null
}

const getters = {
    // ログインチェック 確実に真偽値を返すために二重否定
    check: state => !! state.user,
    // userのstateが存在する場合には名前などの値を返す。ない場合に呼ばれたら空文字を返す
    userid: state => state.user ? state.user.id : '',
    username: state => state.user ? state.user.name : '',
    email: state => state.user ? state.user.email : '',
    icon_path: state => state.user ? state.user.icon_path : '',
    profile_fields: state => state.user ? state.user.profile_fields : ''
}

const mutations = {
    setUser: function (state, user) {
        state.user = user
    },
    setApiStatus: function (state, status) {
        state.apiStatus = status
    },
    setLoginErrorMessages: function (state, messages) {
        state.loginErrorMessages = messages
    },
    setRegisterErrorMessages: function (state, messages) {
        state.registerErrorMessages = messages
    },
    setUpdateErrorMessages: function (state, messages) {
        state.updateErrorMessages = messages
    }
}

const actions = { // それぞれのアクションは、非同期処理の結果によって後続の処理を分岐させる
    // ログイン
    login: async function (context, data) {
        context.commit('setApiStatus', null) // apiStatus始めはnull
        const response = await axios.post('/api/login', data)

        if (response.status === OK) { // レスポンスがOK(200)なら以下の処理を実行
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
        }

        context.commit('setApiStatus', false) // レスポンスがOK以外ならapiStatusをfalse
        // バリデーションエラーの場合は、loginErrorMessagesにエラーメッセージをセットする
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setLoginErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    // ユーザー登録
    register: async function (context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/register', data, {
            headers: {
                'Content-Type': 'multipart/form-data' // 画像の更新のために追加
            }
        })

        if (response.status === CREATED) {
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setRegisterErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    // ログアウト userのstateをnullにする
    logout: async function (context) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/logout')

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', null)
            return false
        }

        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
    },
    // ユーザー更新
    update: async function (context, { data, id }) {
        context.commit('setApiStatus', null)
            const response = await axios.post(`/api/users/${ id }`, data, {
            headers: {
                'Content-Type': 'multipart/form-data', // 画像の更新のために追加
                'X-HTTP-Method-Override': 'PUT', // data = new FormData これをバックエンド側に渡すためにいったんpostで送りputで上書き
            }
        })

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setUpdateErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    // 起動時にログインチェックをおこなう
    currentUser: async function (context) {
        context.commit('setApiStatus', null)
        const response = await axios.get('/api/user/info')
        const user = response.data || null

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', user)
            return false
        }

        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
