<template>
    <section class="l-container">
        <h1 class="l-container__title">コメント一覧</h1>
        <div class="l-container__body">
            <h5><span>最新のコメント</span></h5>
            <ul>
                <li v-bind:id="public_message" class="p-message">
                    <div>
                        案件名: {{ public_message.project.title }}
                    </div>
                    <RouterLink
                        :to="{name: 'projectDetail', params: { id: this.public_message.project.id }}"
                    >
                    <div class="p-message__content">
                    {{ public_message.content }}
                    </div>
                    </RouterLink>
                    <div class="p-message__date">
                    {{ public_message.created_at }}
                    </div>
                </li>
            </ul>
        </div>
        <div class="l-container__body">
            <h5><span>コメントを送った案件一覧</span></h5>
            <div class="c-panel">
                <Project
                    class="c-panel__item"
                    v-for="project in projects"
                    :key="project.id"
                    :item="project"
                />
            </div>
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
                public_message: '',
                projects: []
            }
        },
        methods: {
            async fetchPublicMessages () {
                const response = await axios.get(`/api/public_messages/list`)
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
