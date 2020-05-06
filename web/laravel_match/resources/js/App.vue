<template>
    <div>
        <Navbar />
        <main>
            <div class="l-container">
                <Message />
                <RouterView />
            </div>
        </main>
        <Footer />
    </div>
</template>

<script>
    import Message from './components/Message.vue'
    import Navbar from './components/Navbar.vue'
    import Footer from './components/Footer.vue'
    import { NOT_FOUND, FORBIDDEN, INTERNAL_SERVER_ERROR} from './util'

    export default {
        components: {
            Message,
            Navbar,
            Footer
        },
        computed: { // ストアのステートを参照する
            errorCode () {
                return this.$store.state.error.code
            }
        },
        watch: { // サーバーエラーが発生したら、System.vueに遷移する
            errorCode: {
                handler (val) {
                    if (val === INTERNAL_SERVER_ERROR) {
                        this.$router.push('/500')
                    } else if (val === FORBIDDEN) {
                        this.$router.push('/403')
                    } else if (val === NOT_FOUND) {
                        this.$router.push('/not-found')
                    }
                }
            },
            immediate: true
        },
        $route () {
            this.$store.commit('error/setCode', null)
        }
    }
</script>
