<template>
    <section class="l-container">
        <h1 class="l-container__title">match</h1>
        <form class="c-form" @submit.prevent="login">
            <div v-if="loginErrors" class="c-error">
                <ul v-if="loginErrors.email">
                    <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="loginErrors.password">
                    <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <input type="text" class="c-input c-input__l" v-model="loginForm.email" placeholder="メールアドレス">
            <input type="password" class="c-input c-input__l" v-model="loginForm.password" placeholder="パスワード">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">ログイン</button>
            <RouterLink class="button button--link" to="/password/email">
                パスワードをお忘れの方はこちら
            </RouterLink>
        </form>
    </section>
</template>
<script>
    export default {
        title: 'ログイン',
        data () {
            return {
                loginForm: {
                    email: '',
                    password: ''
                },
            }
        },
        methods: {
            // ログイン
            async login () {
                // authストアのloginアクションを呼び出す
                await this.$store.dispatch('auth/login', this.loginForm)

                if (this.apiStatus) {
                    // loginアクションが成功だった場合、マイページに移動する
                    this.$router.push('/mypage')
                }
            },
            // ストアerror.jsにあるコードをクリアする
            clearError: function() {
                this.$store.commit('auth/setLoginErrorMessages', null)
            }
        },
        created() {
            // 一度エラーが出た後、ブラウザバックなどで戻ってきたときにクリアする
            this.clearError()
        },
        computed: {
            // ストアでの処理が成功したかどうか
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },

            // 入力時にエラーがあった場合、メッセージを格納する
            loginErrors () {
                return this.$store.state.auth.loginErrorMessages
            }
        }
    }
</script>
