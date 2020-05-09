<template>
    <section class="l-container">
        <h1 class="l-container__title">案件登録</h1>
        <form class="c-form" @submit.prevent="projectsRegister">
            <div v-if="registerErrors" class="c-error">
                <ul v-if="registerErrors.title">
                    <li v-for="msg in registerErrors.title" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.type">
                    <li v-for="msg in registerErrors.type" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.minimum_amount">
                    <li v-for="msg in registerErrors.minimum_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.max_amount">
                    <li v-for="msg in registerErrors.max_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.detail">
                    <li v-for="msg in registerErrors.detail" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <input type="text" class="c-input c-input__l" v-model="projectsRegisterForm.title" placeholder="タイトル">
            <div class="c-input__radio__l">
            <label for="one-off" class="c-input__radio">
                <input type="radio" id="one-off" value="one-off" v-model="projectsRegisterForm.type">
                決まった金額を支払う
            </label>
            <label for="service" class="c-input__radio">
                <input type="radio" id="service" value="service" v-model="projectsRegisterForm.type">
                サービス開始後の売り上げを分け合う
            </label>
            </div>
            <div v-if="isOneOff">
                <input type="number" class="c-input c-input__l" max="10000000" v-model="projectsRegisterForm.minimum_amount" placeholder="下限金額">
                <input type="number" class="c-input c-input__l" max="10000000" v-model="projectsRegisterForm.max_amount" placeholder="上限金額">
            </div>
            <textarea cols="30" rows="10" type="text" class="c-input c-input__textarea" id="detail" v-model="projectsRegisterForm.detail" placeholder="詳細"></textarea>
            <button type="submit" class="c-btn c-btn__corp c-btn__l">案件を登録する</button>
        </form>
    </section>
</template>
<script>
    import {CREATED, UNPROCESSABLE_ENTITY} from "../util";

    export default {
        data () {
            return {
                projectsRegisterForm: {
                    title: '',
                    type: 'one-off',
                    minimum_amount: '',
                    max_amount: '',
                    detail: ''
                },
                registerErrors: null
            }
        },
        methods: {
            // 案件を登録する
            async projectsRegister () {
                // projectsRegisterFormの入力内容で、ProjectController@createを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.post('/api/projects/register', this.projectsRegisterForm)

                // バリデーションエラー
                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.registerErrors = response.data.errors
                    return false
                } else if (response.status !== CREATED) { // その他のエラー
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 成功だった場合
                // 1.ストアにメッセージを格納する
                this.$store.commit('message/setContent', {
                    content: '案件を登録しました！',
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
            // 案件のタイプで'one-off'が選択されていればtrue、'service'ならfalseを返却
            isOneOff () {
                return this.projectsRegisterForm.type === 'one-off';
            }
        }
    }
</script>
