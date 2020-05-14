<template>
    <section class="l-container">
        <h1 class="l-container__title">メッセージ一覧</h1>
        <div class="l-container__body" v-if="isUnreadMessage">
            <h5 class="l-container__subtitle">未読のメッセージ</h5>
            <ul>
                <li v-for="private_message in unread_private_messages" v-bind:id="unread_private_messages" class="p-message">
                    <div>
                        案件名: {{ private_message.project.title }}
                    </div>
                    <RouterLink
                        :to="{name: 'privateMessagesDetail', params: { id: private_message.project_id }}"
                    >
                    <div class="p-message__content">
                    {{ private_message.content }}
                    </div>
                    </RouterLink>
                    <div class="p-message__date">
                    {{ private_message.created_at }}
                    </div>
                </li>
            </ul>
        </div>
        <div class="l-container__body" v-else>
            <h5 class="l-container__subtitle">未読のメッセージはありません。</h5>
        </div>
        <div class="l-container__body">
            <h5 class="l-container__subtitle">メッセージをやりとりした案件一覧</h5>
            <div class="c-panel">
                <Project
                    class="c-panel__item"
                    v-for="project in projects"
                    :key="project.id"
                    :item="project"
                />
            </div>
        </div>
        <div class="l-container__body">
            <h5 class="l-container__subtitle">やりとりしたメッセージ</h5>
            <ul>
                <li v-for="private_message in private_messages" v-bind:id="private_messages" class="p-message">
                    <div>
                        案件名: {{ private_message.project.title }}
                    </div>
                    <RouterLink
                        :to="{name: 'privateMessagesDetail', params: { id: private_message.project_id }}"
                    >
                        <div class="p-message__content">
                            {{ private_message.content }}
                        </div>
                    </RouterLink>
                    <div class="p-message__date">
                        {{ private_message.created_at }}
                    </div>
                </li>
            </ul>
        </div>
    </section>
</template>
<script>
    import { OK } from '../util'
    import Project from '../components/Project.vue'

    export default {
        components: {
            Project
        },
        data () {
            return {
                unread_private_messages: [],
                private_messages: [],
                projects: []
            }
        },
        methods: {
            async fetchPrivateMessages () {
                const response = await axios.get(`/api/private_messages/list`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.unread_private_messages = response.data.unread_private_messages
                this.private_messages = response.data.exchanged_private_messages.data
                this.projects = response.data.exchanged_private_messages_projects
            }
        },
        computed: {
            // 未読のメッセージがあるかどうか
            isUnreadMessage () {
                return this.unread_private_messages > 1
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
