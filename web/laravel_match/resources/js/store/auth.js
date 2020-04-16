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
    setUser (state, user) {
        state.user = user
    },
    setApiStatus (state, status) {
        state.apiStatus = status
    },
    setLoginErrorMessages (state, messages) {
        state.loginErrorMessages = messages
    },
    setRegisterErrorMessages (state, messages) {
        state.registerErrorMessages = messages
    },
    setUpdateErrorMessages (state, messages) {
        state.updateErrorMessages = messages
    }
}

const actions = { // それぞれのアクションは、非同期処理の結果によって後続の処理を分岐させる
    // ログイン
    async login (context, data) {
        context.commit('setApiStatus', null) // apiStatus始めはnull
        const response = await axios.post('/login', data)

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
    async register (context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/register', data, {
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
            console.log('422がきているよ')
            console.log(response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    // ログアウト userのstateをnullにする
    async logout (context) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/logout')

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', null)
            return false
        }

        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
    },
    // ユーザー更新
    async update (context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/users/' + data.get('id'), data, {
            headers: {
                'Content-Type': 'multipart/form-data', // 画像の更新のために追加
                'X-HTTP-Method-Override': 'PUT', // data = new FormData これをバックエンド側に渡すためにいったんpostで送りputで上書き
            }
        })
        // context.commit('setUser', response.data)
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
    async currentUser (context) {
        context.commit('setApiStatus', null)
        const response = await axios.get('/user')
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
