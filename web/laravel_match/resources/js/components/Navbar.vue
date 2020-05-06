<template>
    <div>
    <header class="l-header">
        <h1><RouterLink class="l-header__title" to="/">match</RouterLink></h1>
    <nav class="l-nav">
        <div v-if="isLogin">
        <ul class="c-menu">
            <li class="c-menu__item"><RouterLink class="c-menu__link" to="/projects/list">案件を探す</RouterLink></li>
            <li class="c-menu__item"><RouterLink class="c-menu__link" to="/projects/register">案件を登録する</RouterLink></li>
            <li class="c-menu__item"><RouterLink class="c-menu__link" to="/public_messages/list">公開メッセージ</RouterLink></li>
            <li class="c-menu__item"><RouterLink class="c-menu__link" to="/private_messages/list">ダイレクトメッセージ</RouterLink></li>
            <li class="c-menu__item">
                <RouterLink class="c-menu__link" to="/mypage">マイページ</RouterLink>
                <img :src="icon_path" alt="アイコン画像"  height="30" class="imgIcon">
            </li>
            <li class="c-menu__item"><RouterLink class="c-menu__link" :to="{name: 'edit', params: { id: userid }}">本人情報</RouterLink></li>
        </ul>
            <button class="c-btn" @click="logout">ログアウト</button>
        </div>
        <div v-else>
        <ul class="c-menu">
            <li class="c-menu__item"><RouterLink class="c-menu__link" to="/login">ログイン</RouterLink></li>
            <li class="c-menu__item"><RouterLink class="c-menu__link" to="/register">ユーザー登録</RouterLink></li>
        </ul>
        </div>
    </nav>
    </header>
    </div>
</template>
<script>
    export default {
        // data () {
        //     return {
        //         id: this.$store.getters['auth/userid']
        //     }
        // },
        methods: {
            async logout () {
                await this.$store.dispatch('auth/logout')

                if (this.apiStatus) {
                    this.$router.push('/login')
                }
            }
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },
            isLogin () {
                return this.$store.getters['auth/check']
            },
            userid () {
                return this.$store.getters['auth/userid']
            },
            icon_path () {
                return this.$store.getters['auth/icon_path']
            }
        }
    }
</script>
