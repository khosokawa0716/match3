<template>
    <section class="l-container">
        <h1 class="l-container__title">パスワードを忘れてしまった場合</h1>
        <form class="c-form" @submit.prevent="sendEmail">
            <div v-if="passResetEmailErrors" class="c-error">
                <ul v-if="passResetEmailErrors.email">
                    <li v-for="msg in passResetEmailErrors.email" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <p>パスワード再設定用のログインリンクをお送りしますので、メールアドレスを入力してください。</p>
            <input type="text" class="c-input c-input__l" v-model="passResetEmailForm.email" placeholder="メールアドレス">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">リンクを送信する</button>
        </form>
    </section>
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
                this.$store.commit('error/setCode', null)

                // Auth\ForgotPasswordController@sendResetLinkEmailを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.post('/api/password/email', this.passResetEmailForm)

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
                    this.$store.commit('message/setContent', {
                    content: 'メールをお送りしました。メールのボタンを押して、パスワードリセットの画面を開いてください。\n' +
                        'パスワードリセットの画面が開けましたら、この画面は閉じてください。\n' +
                        'しばらく経ってもメールが届かない場合には、メールアドレスが間違っている可能性があります。',
                    timeout: 30000
                })
            }
        }
    }
</script>
