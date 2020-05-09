<template>
    <section class="l-container">
        <h1 class="l-container__title">案件の編集</h1>
        <form class="c-form" @submit.prevent="projectsUpdate">
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
            <input type="text" class="c-input c-input__l" v-model="projectsUpdateForm.title" placeholder="タイトル">
            <div class="c-input__radio__l">
            <label for="one-off" class="c-input__radio">
                <input type="radio" id="one-off" value="one-off" v-model="projectsUpdateForm.type">
                決まった金額を支払う
            </label>
            <label for="service" class="c-input__radio">
                <input type="radio" id="service" value="service" v-model="projectsUpdateForm.type">
                サービス開始後の売り上げを分け合う
            </label>
            </div>
            <div v-if="isOneOff">
                <input type="number" class="c-input c-input__l" max="10000000" v-model="projectsUpdateForm.minimum_amount" placeholder="下限金額">
                <input type="number" class="c-input c-input__l" max="10000000" v-model="projectsUpdateForm.max_amount" placeholder="上限金額">
            </div>
            <textarea type="text" class="c-input c-input__textarea" v-model="projectsUpdateForm.detail" placeholder="詳細"></textarea>
            <button type="submit" class="c-btn c-btn__corp c-btn__l">案件を更新する</button>
        </form>
    </section>
</template>
<script>
    import {OK, UNPROCESSABLE_ENTITY} from "../util";

    export default {
        data () {
            return {
                projectsUpdateForm: {
                    id: this.$route.params.id,
                    title: '',
                    type: '',
                    minimum_amount: '',
                    max_amount: '',
                    detail: ''
                },
                updateErrors: null
            }
        },
        methods: {
            async fetchProject () {
                // await this.$store.dispatch('project/edit', this.projectsUpdateForm.id)

                const response = await axios.get(`/api/projects/${this.projectsUpdateForm.id}/edit`, this.projectsUpdateForm.id)
                // console.dir(response)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.project = response.data

                this.projectsUpdateForm.title = this.project.title
                this.projectsUpdateForm.type = this.project.type
                this.projectsUpdateForm.minimum_amount = this.project.minimum_amount
                this.projectsUpdateForm.max_amount = this.project.max_amount
                this.projectsUpdateForm.detail = this.project.detail
            },

            // 案件更新の処理
            async projectsUpdate () {
                //
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
            clearError () {
                this.$store.commit('error/setCode', null)
            }
        },
        created() {
            // 一度エラーが出た後、ブラウザバックなどで戻ってきたときにクリアする
            this.clearError ()
        },
        computed: {
            isOneOff () {
                return this.projectsUpdateForm.type === 'one-off';
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchProject()
                },
                immediate: true
            }
        }
    }
</script>
