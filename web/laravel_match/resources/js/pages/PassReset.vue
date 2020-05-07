<template>
    <section class="l-container">
        <form class="c-form" @submit.prevent="passReset">
            <div v-if="passResetErrors" class="c-error">
                <ul v-if="passResetErrors.password">
                    <li v-for="msg in passResetErrors.password" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="passResetErrors.password_confirmation">
                    <li v-for="msg in passResetErrors.password_confirmation" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <input type="password" class="c-input c-input__l" v-model="passResetForm.password" placeholder="新しいパスワード">
            <input type="password" class="c-input c-input__l" v-model="passResetForm.password_confirmation" placeholder="新しいパスワード（確認用）">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">パスワードを変更する</button>
        </form>
    </section>
</template>
<script>
    import {OK, UNPROCESSABLE_ENTITY} from "../util";

    export default {
        data() {
            return {
                passResetForm: { // emailとtokenは、GETパラメータから取得する
                    email: this.$route.query.email,
                    password: '',
                    password_confirmation: '',
                    token: this.$route.params.pathMatch
                },
                passResetErrors: null
            }
        },
        methods: {
            // パスワードリセット、その後自動でログインをおこないマイページに遷移する
            async passReset () {
                // Auth\ResetPasswordController@resetを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.post('/api/password/reset/', this.passResetForm)
                console.dir(response)

                // バリデーションエラー
                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.passResetErrors = response.data.errors
                    return false
                } else if (response.status !== OK) { // その他のエラー
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // パスワードリセットが成功したら、authストアのloginアクションを呼び出す
                await this.$store.dispatch('auth/login', this.passResetForm)

                if (this.apiStatus) {
                    // loginアクションが成功だった場合、ストアにメッセージを格納する
                    this.$store.commit('message/setContent', {
                        content: 'お客様のパスワードを更新しました！',
                        timeout: 5000
                    })
                    // マイページに遷移する
                    this.$router.push('/mypage')
                }
            }
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            }
        }
    }
</script>
