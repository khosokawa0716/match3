<template>
    <div>
        <h1 class="l-container__title">マイページ</h1>
        <p>未読のメッセージ: {{ unread_private_messages }}件</p>
        <RouterLink class="button button--link" to="/projects/list">
            案件の一覧を見る
        </RouterLink>
        <RouterLink class="button button--link" to="/projects/register">
            案件を登録する
        </RouterLink>
        <RouterLink class="button button--link" :to="{name: 'edit', params: { id: this.id }}">
            お客様の登録情報
        </RouterLink>
        <RouterLink class="button button--link" to="/public_messages/list">
            公開メッセージ一覧を見る
        </RouterLink>
        <RouterLink class="button button--link" to="/private_messages/list">
            非公開メッセージ一覧を見る
        </RouterLink>
        <h5>登録した案件一覧</h5>
        <div>
            <Project
                class=""
                v-for="project in registered_projects"
                :key="project.id"
                :item="project"
            />
        </div>
        <h5>応募した案件一覧</h5>
        <div>
            <Project
                class=""
                v-for="project in applied_projects"
                :key="project.id"
                :item="project"
            />
        </div>
        <h5>やりとりした公開メッセージ</h5>
        <ul>
            <li v-for="public_message in exchanged_public_messages" v-bind="public_message.id">
                {{ public_message.author.name }}
                {{ public_message.content }}
                {{ public_message.created_at }}
            </li>
        </ul>
        <h5>やりとりした非公開メッセージ</h5>
        <ul>
            <li v-for="private_message in exchanged_private_messages" v-bind="private_message.id">
                {{ private_message.author.name }}
                {{ private_message.content }}
                {{ private_message.created_at }}
            </li>
        </ul>
    </div>
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
                id: this.$store.getters['auth/userid'],
                registered_projects: [],
                applied_projects: [],
                exchanged_public_messages: [],
                exchanged_private_messages: [],
                unread_private_messages: null
            }
        },
        methods: {
            async fetchProjects () {
                const response = await axios.get('/api/mypage')
                // console.dir(response);

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.registered_projects = response.data.registered_projects.data
                this.applied_projects = response.data.applied_projects.data
                this.exchanged_public_messages = response.data.exchanged_public_messages.data
                this.exchanged_private_messages = response.data.exchanged_private_messages.data
                this.unread_private_messages = response.data.unread_private_messages
            },
            // ストアerror.jsにあるコードをクリアする
            clearError () {
                this.$store.commit('error/setCode', null)
            }
        },
        created() {
            // 一度エラーが出た後、ブラウザバックなどで戻ってきたときにクリアする
            this.clearError ()
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchProjects()
                },
                immediate: true
            }
        }
    }
</script>
