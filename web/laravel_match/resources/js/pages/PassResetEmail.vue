<template>
    <div>
        <form @submit.prevent="sendEmail">
            <div v-if="passResetEmailErrors" class="errors">
                <ul v-if="passResetEmailErrors.email">
                    <li v-for="msg in passResetEmailErrors.email" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <div>
                <h2>ログインできない場合</h2>
                <p>パスワード再設定用のログインリンクをお送りしますので、メールアドレスを入力してください。</p>
                <label for="pass-reset-email">メールアドレス</label>
                <input type="text" class="form__item" id="pass-reset-email" v-model="passResetEmailForm.email">
            </div>
            <div>
                <button type="submit" class="button button--inverse">リンクを送信する</button>
            </div>
        </form>

        <RouterLink class="button button--link" to="/login">
            ログインに戻る
        </RouterLink>
        <RouterLink class="button button--link" to="/register">
            ユーザー登録
        </RouterLink>
    </div>
</template>
<script>
    import {OK, UNPROCESSABLE_ENTITY} from "../util";

    export default {
        data() {
            return {
                passResetEmailForm: {
                    email: ''
                },
                passResetEmailErrors: null
            }
        },

        // パスワードリセットのためのメールを送信する
        methods: {
            async sendEmail () {
                // フォーム上のメッセージをクリアする
                this.passResetEmailErrors = null
                // this.$store.commit('error/setCode', null)

                // Auth\ForgotPasswordController@sendResetLinkEmailを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.post('/api/password/email', this.passResetEmailForm)
                console.dir(response)

                // バリデーションエラー
                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.passResetEmailErrors = response.data.errors
                    return false
                } else if (response.status !== OK) { // その他のエラー
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 成功の場合

                // 画面上にメッセージを表示する
                this.passResetEmailErrors = 'メールをお送りしました。メールのボタンを押して、パスワードリセットの画面を開いてください。' +
                    'パスワードリセットの画面が開けましたら、この画面は閉じてください。' +
                    'しばらく経ってもメールが届かない場合には、メールアドレスが間違っている可能性があります。'

                //     this.$store.commit('message/setContent', {
                //     content: 'メールをお送りしました。メールのボタンを押して、パスワードリセットの画面を開いてください。' +
                //         'パスワードリセットの画面が開けましたら、この画面は閉じてください。' +
                //         'しばらく経ってもメールが届かない場合には、メールアドレスが間違っている可能性があります。',
                //     timeout: 20000
                // })
            }
        }
    }
</script>
