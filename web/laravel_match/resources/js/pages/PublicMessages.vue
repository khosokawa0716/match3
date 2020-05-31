<template>
    <section class="l-container">
        <h1 class="l-container__title">コメント一覧</h1>
        <div class="l-container__body">
            <h5 class="l-container__subtitle">最新のコメント</h5>
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
            <h5 class="l-container__subtitle">コメントを送った案件一覧</h5>
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
        title: 'コメント一覧',
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
            // コメント一覧画面に表示する情報をとってくる
            async fetchPublicMessages () {
                // PublicMessagesController@showを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.get(`/api/public_messages/list`)

                // エラーの場合
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 表示する2つの情報をプロパティに代入する
                this.public_message = response.data.latest_public_message
                this.projects = response.data.exchanged_messages_projects
            }
        },
        watch: {
            $route: {
                // 画面の表示のさいにfetchPublicMessagesメソッドを実行する
                async handler () {
                    await this.fetchPublicMessages()
                },
                immediate: true
            }
        }
    }
</script>
