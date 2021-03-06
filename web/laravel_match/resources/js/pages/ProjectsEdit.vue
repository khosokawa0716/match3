<template>
    <section>
        <div class="c-breadcrumb">
            <breadcrumb :breadcrumbs="breadcrumbs" />
        </div>
        <div class="l-container">
        <h1 class="l-container__title">案件の編集</h1>
        <form class="c-form" @submit.prevent="projectsUpdate">
            <div v-if="isApplied" class="c-error">
                <ul>
                    <li>この案件には応募があり、募集の状態以外の項目は変更できません。</li>
                </ul>
            </div>
            <div v-if="updateErrors" class="c-error">
                <ul v-if="updateErrors.title">
                    <li v-for="msg in updateErrors.title" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.type">
                    <li v-for="msg in updateErrors.type" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.minimum_amount">
                    <li v-for="msg in updateErrors.minimum_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.max_amount">
                    <li v-for="msg in updateErrors.max_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.detail">
                    <li v-for="msg in updateErrors.detail" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <input type="hidden" name="_method" value="PUT">
            <input type="text" class="c-input c-input__l" v-model="projectsUpdateForm.title" placeholder="タイトル（3〜60文字）" v-bind:readonly="isApplied">
            <div class="c-input__radio__l">
                <label for="recruiting" class="c-input__radio">
                    <input type="radio" id="recruiting" value="1" v-model="projectsUpdateForm.status">
                    募集中
                </label>
                <label for="end_of_recruitment" class="c-input__radio">
                    <input type="radio" id="end_of_recruitment" value="0" v-model="projectsUpdateForm.status">
                    募集終了
                </label>
            </div>
            <div class="c-input__radio__l">
                <label for="one-off" class="c-input__radio">
                    <input type="radio" id="one-off" value="one-off" v-model="projectsUpdateForm.type" v-bind:disabled="isApplied">
                    決まった金額を支払う
                </label>
                <label for="service" class="c-input__radio">
                    <input type="radio" id="service" value="service" v-model="projectsUpdateForm.type" v-bind:disabled="isApplied">
                    サービス開始後の売り上げを分け合う
                </label>
            </div>
            <div v-if="isOneOff">
                <input type="number" class="c-input c-input__l" v-model.number="projectsUpdateForm.minimum_amount" placeholder="下限金額（1,000〜10,000,000円）" v-bind:readonly="isApplied">
                <input type="number" class="c-input c-input__l" v-model.number="projectsUpdateForm.max_amount" placeholder="上限金額（1,000〜10,000,000円）" v-bind:readonly="isApplied">
            </div>
            <textarea type="text" class="c-input c-input__textarea" v-model="projectsUpdateForm.detail" placeholder="詳細（3〜1000文字）" v-bind:readonly="isApplied"></textarea>
            <button type="submit" class="c-btn c-btn__corp c-btn__l">案件を更新する</button>
        </form>
        </div>
    </section>
</template>
<script>
    import { OK, UNPROCESSABLE_ENTITY } from '../util'
    import Breadcrumb from '../components/Breadcrumb'

    export default {
        components: {
            Breadcrumb
        },
        title: '案件の編集',
        data () {
            return {
                breadcrumbs: [
                    {
                        name: 'ホーム',
                        path: '/'
                    },
                    {
                        name: '案件の編集'
                    }
                ],
                isApplied: false,
                projectsUpdateForm: {
                    id: this.$route.params.id,
                    title: '',
                    status: '',
                    type: '',
                    minimum_amount: '',
                    max_amount: '',
                    detail: ''
                },
                updateErrors: null
            }
        },
        methods: {
            // 編集しようとする案件をとってくる
            fetchProject: async function () {
                // ProjectController@editの起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.get(`/api/projects/${this.projectsUpdateForm.id}/edit`, this.projectsUpdateForm.id)

                // エラーの処理
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 成功の場合、案件の情報をプロパティに代入
                // this.project = response.data.project
                this.isApplied = response.data.is_applied

                this.projectsUpdateForm.title = response.data.project.title
                this.projectsUpdateForm.status = response.data.project.status
                this.projectsUpdateForm.type = response.data.project.type
                this.projectsUpdateForm.minimum_amount = response.data.project.minimum_amount
                this.projectsUpdateForm.max_amount = response.data.project.max_amount
                this.projectsUpdateForm.detail = response.data.project.detail
            },

            // 案件更新の処理
            projectsUpdate: async function () {
                // ProjectController@updateの起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.put(`/api/projects/${this.projectsUpdateForm.id}/edit`, this.projectsUpdateForm)

                // バリデーションエラー
                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.updateErrors = response.data.errors
                    return false
                } else if (response.status !== OK) { // その他のエラー
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 成功だった場合
                // 1.ストアにメッセージを格納する
                this.$store.commit('message/setContent', {
                    content: '案件を更新しました！',
                    timeout: 5000
                })
                // 2.マイページに移動する
                this.$router.push('/mypage')
            },
            // ストアerror.jsにあるコードをクリアする
            clearError: function() {
                this.$store.commit('error/setCode', null)
            }
        },
        created: function() {
            // 一度エラーが出た後、ブラウザバックなどで戻ってきたときにクリアする
            this.clearError ()
        },
        computed: {
            // 支払い方法のタイプが「依頼のときに一定の金額を支払う」かどうか
            isOneOff: function() {
                return this.projectsUpdateForm.type === 'one-off';
            }
        },

        // 初期値を反映させるために、画面遷移直後にfetchProjectメソッドを呼ぶ
        watch: {
            $route: {
                handler: async function () {
                    await this.fetchProject()
                },
                immediate: true
            }
        }
    }
</script>
