<template>
    <div>
        <form @submit.prevent="passReset">
            <div><input type="password" v-model="passResetForm.password" placeholder="新しいパスワード"></div>
            <div><input type="password" v-model="passResetForm.password_confirmation" placeholder="新しいパスワード（確認用）"></div>
            <div><button type="submit" class="button button--inverse">パスワードを変更する</button></div>
        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                passResetForm: { // emailとtokenは、GETパラメータから取得する
                    email: this.$route.query.email,
                    password: '',
                    password_confirmation: '',
                    token: this.$route.params.pathMatch
                }
            }
        },
        methods: {
            async passReset () {
                await axios.post('/password/reset/', this.passResetForm)
            }
        },
    }
</script>
