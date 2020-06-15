<template>
    <div class="l-wrapper">
        <Navbar />
        <Breadcrumb />
        <main>
                <Message />
                <RouterView />
        </main>
        <Footer />
    </div>
</template>

<script>
    import Message from './components/Message.vue'
    import Navbar from './components/Navbar.vue'
    import Footer from './components/Footer.vue'
    import {UNAUTHORIZED, UNAUTHORIZED_CSRF, NOT_FOUND, FORBIDDEN, INTERNAL_SERVER_ERROR} from './util'
    import Breadcrumb from './components/Breadcrumb'

    export default {
        components: {
            Navbar,
            Breadcrumb,
            Message,
            Footer
        },
        computed: { // ストアのステートを参照する
            errorCode: function() {
                return this.$store.state.error.code
            }
        },
        watch: { // サーバーエラーが発生したら、System.vueに遷移する
            errorCode: {
                handler: async function (val) {
                    if (val === INTERNAL_SERVER_ERROR) {
                        this.$router.push('/500')
                    } else if (val === FORBIDDEN) {
                        this.$router.push('/403')
                    } else if (val === UNAUTHORIZED) {
                        // トークンをリフレッシュ
                        await axios.get('/api/refresh-token')
                        // ストアのuserをクリア
                        this.$store.commit('auth/setUser', null)
                        this.$router.push('/401')
                    }else if (val === UNAUTHORIZED_CSRF) {
                        // トークンをリフレッシュ
                        await axios.get('/api/refresh-token')
                        // ストアのuserをクリア
                        this.$store.commit('auth/setUser', null)
                        this.$router.push('/419')
                    } else if (val === NOT_FOUND) {
                        this.$router.push('/not-found')
                    } else {
                        this.$router.push('/unexpected-error')
                    }
                }
            },
            immediate: true
        },
        $route: function () {
            this.$store.commit('error/setCode', null)
        }
    }
</script>
