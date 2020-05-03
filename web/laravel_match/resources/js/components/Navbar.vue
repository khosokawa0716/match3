<template>
    <nav class="navbar">
        <RouterLink class="navbar__brand" to="/">
            match
        </RouterLink>
        <div class="navbar__menu">
            <div v-if="isLogin" class="navbar__item">
                <RouterLink class="button button--link" to="/mypage">
                    マイページ
                </RouterLink>
                <button class="button button--link" @click="logout">ログアウト</button>
            </div>
            <span v-if="isLogin" class="navbar__item">
                {{ username }}様
            </span>
            <img v-if="isLogin" :src="icon_path" alt="アイコン画像"  height="20" class="imgIcon">
            <div v-else class="navbar__item">
                <RouterLink class="button button--link" to="/login">
                    ログイン
                </RouterLink>
                <RouterLink class="button button--link" to="/register">
                    ユーザー登録
                </RouterLink>
            </div>
        </div>
    </nav>
</template>
<script>
    export default {
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
            username () {
                return this.$store.getters['auth/username']
            },
            icon_path () {
                return this.$store.getters['auth/icon_path']
            }
        }
    }
</script>
