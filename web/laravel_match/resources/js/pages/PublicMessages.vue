<template>
    <section class="l-container">
        <h1 class="l-container__title">公開メッセージ一覧</h1>
        <div>
            <h5>最新の公開メッセージ1件</h5>
            <ul>
                <li v-bind:id="public_message">
                    {{ public_message.content }}
                    {{ public_message.created_at }}
                </li>
            </ul>
        </div>
        <div>
            <h5>公開メッセージを送った案件一覧</h5>
            <ul>
                <li v-for="project in projects" v-bind:id="projects">
                    {{ project.title }}
                    {{ project.detail }}
                </li>
            </ul>
        </div>
    </section>
</template>
<script>
    import { OK } from '../util'
    export default {
        data () {
            return {
                public_message: '',
                projects: []
            }
        },
        methods: {
            async fetchPublicMessages () {
                const response = await axios.get(`/api/public_messages/list`)
                console.dir(response)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.public_message = response.data.latest_public_message
                this.projects = response.data.exchanged_messages_projects
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchPublicMessages()
                },
                immediate: true
            }
        }
    }
</script>
