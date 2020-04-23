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
    title: state => state.project ? state.project.title : '',
    type: state => state.project ? state.project.type : '',
    minimum_amount: state => state.project ? state.project.minimum_amount : '',
    max_amount: state => state.project ? state.project.max_amount : '',
    detail: state => state.project ? state.project.detail : '',
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
    // 案件編集画面の表示。表示だけでなくstateに編集するプロジェクトを格納する
    // フォームを表示するだけならstateに入れる必要はないが、変更前の値を表示させるため
    async edit (context, data) {
        console.log(data)
        // console.log(data.id) // これはundefined
        context.commit('setApiStatus', null) // apiStatus始めはnull
        const response = await axios.get('/projects/' + data + '/edit', data)

        if (response.status === OK) { // レスポンスがOK(200)なら以下の処理を実行
            console.log('editアクションでレスポンス200を取得')
            context.commit('setApiStatus', true)
            console.dir(response.data)
            context.commit('setProject', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
    },
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
        console.log(data.id);
        context.commit('setApiStatus', null)
        const response = await axios.put('/projects/' + data.id, data)

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
    },
    // async detail (context, data) {
    //     context.commit('setApiStatus', null)
    //     const response = await axios.get('/project/detail/' + data, data)
    //
    //     if (response.status === OK) { // レスポンスがOK(200)なら以下の処理を実行
    //         console.log('editアクションでレスポンス200を取得')
    //         context.commit('setApiStatus', true)
    //         console.dir(response.data)
    //         context.commit('setProject', response.data)
    //         return false
    //     }
    //
    //     context.commit('setApiStatus', false)
    //     context.commit('error/setCode', response.status, { root: true })
    // },
    // // 画面更新時にプロジェクトのストアを更新しない
    async currentProject (context) {
        console.log('currentProject起動!!')
    context.commit('setApiStatus', null)
    const response = await axios.get('/project/info')
        console.dir(response)
    const project = response.data || null
        console.dir(project)

    if (response.status === OK) {
        context.commit('setApiStatus', true)
        context.commit('setProject', project)
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
