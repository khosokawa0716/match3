<template>
    <section class="l-container">
        <h1 class="l-container__title">非公開メッセージ一覧</h1>
        <div>
            <h5>未読の非公開メッセージ</h5>
            <ul>
                <li v-for="private_message in private_messages" v-bind:id="private_messages">
                    {{ private_message.content }}
                    {{ private_message.created_at }}
                    {{ private_message.project_id }}
                    <RouterLink class="button button--link" :to="{name: 'privateMessagesDetail', params: { id: private_message.project_id }}">
                        非公開メッセージ詳細を見る
                    </RouterLink>
                </li>
            </ul>
        </div>
        <div>
            <h5>非公開メッセージを送った案件一覧</h5>
            <ul>
                <li v-for="project in projects" v-bind:id="projects">
                    {{ project.title }}
                    {{ project.detail }}
                    {{ project.id }}
                    <RouterLink class="button button--link" :to="{name: 'privateMessagesDetail', params: { id: project.id }}">
                        非公開メッセージ詳細を見る
                    </RouterLink>
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
                private_messages: [],
                projects: []
            }
        },
        methods: {
            async fetchPrivateMessages () {
                const response = await axios.get(`/api/private_messages/list`)
                console.dir(response)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.private_messages = response.data.unread_private_messages
                this.projects = response.data.exchaged_private_messages_projects
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchPrivateMessages()
                },
                immediate: true
            }
        }
    }
</script>
