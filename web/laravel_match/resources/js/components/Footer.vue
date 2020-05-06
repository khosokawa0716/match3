<template>
    <footer class="l-footer">
        <p>Copyright &copy; One-Stop Koshigoe LLC. All Rights Reserved</p>
<!--        <button v-if="isLogin" class="button button&#45;&#45;link" @click="logout">ログアウト</button>-->
<!--        <RouterLink v-else class="button button&#45;&#45;link" to="/login">-->
<!--            ログイン-->
<!--        </RouterLink>-->
    </footer>
</template>
<!--<script>-->
<!--    export default {-->
<!--        methods: {-->
<!--            async logout () {-->
<!--                await this.$store.dispatch('auth/logout')-->

<!--                if (this.apiStatus) {-->
<!--                    this.$router.push('/login')-->
<!--                }-->
<!--            }-->
<!--        },-->
<!--        computed: {-->
<!--            apiStatus () {-->
<!--                return this.$store.state.auth.apiStatus-->
<!--            },-->
<!--            isLogin () {-->
<!--                return this.$store.getters['auth/check']-->
<!--            }-->
<!--        }-->
<!--    }-->
<!--</script>-->
