<template>
    <div>
        <form @submit.prevent="sendEmail">
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
    export default {
        data() {
            return {
                passResetEmailForm: {
                    email: ''
                }
            }
        },
        methods: {
            async sendEmail () {
                await axios.post('/api/password/email', this.passResetEmailForm)
            }
        }
    }
</script>
