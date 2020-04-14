const state = {
    user: null
}

const getters = {
    // ログインチェック 確実に真偽値を返すために二重否定
    check: state => !! state.user,
    // userのstateが存在する場合には名前などの値を返す。ない場合に呼ばれたら空文字を返す
    username: state => state.user ? state.user.name : '',
    email: state => state.user ? state.user.email : '',
    icon_path: state => state.user ? state.user.icon_path : '',
    profile_fields: state => state.user ? state.user.profile_fields : ''
}

const mutations = {
    setUser (state, user) {
        state.user = user
    }
}

const actions = {
    // ユーザー登録
    async register (context, data) {
        const response = await axios.post('/register', data, {
            headers: { // 画像の登録があるために以下3行を追加
                'Content-Type': 'multipart/form-data'
            }
        })
        context.commit('setUser', response.data)
    },
    // ユーザー更新
    async update (context, data) {
        const response = await axios.post('/users/{user}', data, {
            headers: { // 画像の登録があるために以下3行を追加
                'Content-Type': 'multipart/form-data'
            }
        })
        context.commit('setUser', response.data)
    },
    // ログイン
    async login (context, data) {
        const response = await axios.post('/login', data)
        context.commit('setUser', response.data)
    },
    // ログアウト userのstateをnullにする
    async logout (context) {
        const response = await axios.post('/logout')
        context.commit('setUser', null)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
