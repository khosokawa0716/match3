<template>
    <div>
        <h1>ログイン</h1>
        <form class="form" @submit.prevent="login">
            <div v-if="loginErrors" class="errors">
                <ul v-if="loginErrors.email">
                    <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="loginErrors.password">
                    <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <label for="login-email">メールアドレス</label>
            <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
            <label for="login-password">パスワード</label>
            <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
            <div class="form__button">
                <button type="submit" class="button button--inverse">login</button>
            </div>
        </form>
        <div>
            <RouterLink class="button button--link" to="/password/email">
                パスワードをお忘れの方はこちら
            </RouterLink>
        </div>
    </div>
</template>
<script>
    export default {
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
            clearError () {
                this.$store.commit('auth/setLoginErrorMessages', null)
            }
        },
        created() {
            this.clearError()
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },
            loginErrors () {
                return this.$store.state.auth.loginErrorMessages
            }
        }
    }
</script>
