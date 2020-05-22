<template>
    <section class="l-container">
        <h1 class="l-container__title">マイページ</h1>

        <div class="l-container__nav">
            <a href="#" @click="toRegistered_projects" class="item">
                登録した案件一覧
            </a>
            <a href="#" @click="toApplied_projects" class="item">
                応募した案件一覧
            </a>
            <a href="#" @click="toComment" class="item">
                やりとりしたコメント
            </a>
            <a href="#" @click="toMessage" class="item">
                やりとりしたメッセージ
            </a>
        </div>
        <div class="l-container__body" v-if="isUnreadMessage">
            <div>
                <RouterLink to="/private_messages/list">
                    <p class="p-info">
                        <i class="fas fa-bell fa-x"></i>&nbsp;
                        <span>{{ number_unread_private_messages }}件の未読メッセージがあります。</span>
                    </p>
                </RouterLink>
            </div>
        </div>
        <div class="l-container__body" id="registered_projects">
            <h5 class="l-container__subtitle">登録した案件一覧</h5>
            <div class="c-panel">
                <Project
                    class="c-panel__item"
                    v-for="project in registered_projects"
                    :key="project.id"
                    :item="project"
                />
            </div>
        </div>
        <div class="l-container__body" id="applied_projects">
            <h5 class="l-container__subtitle">応募した案件一覧</h5>
            <div class="c-panel">
                <Project
                    class="c-panel__item"
                    v-for="project in applied_projects"
                    :key="project.id"
                    :item="project"
                />
            </div>
        </div>
        <div class="l-container__body" id="comment">
            <h5 class="l-container__subtitle">やりとりしたコメント</h5>
            <ul>
                <li v-for="public_message in exchanged_public_messages" v-bind="public_message.id" class="p-message">
                    <div>
                        案件名: {{ public_message.project.title }}
                    </div>
                    <RouterLink
                        :to="{name: 'projectDetail', params: { id: public_message.project.id }}"
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
        <div class="l-container__body" id="message">
            <h5 class="l-container__subtitle">やりとりしたメッセージ</h5>
            <ul>
                <li v-for="private_message in exchanged_private_messages" v-bind="private_message.id" class="p-message">
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
        <div class="u-page-button" @click="scrollTop">
            <i class="fas fa-chevron-up Page-Btn-Icon"></i>
        </div>
    </section>
</template>
<script>
    import { OK } from '../util'
    import Project from '../components/Project.vue'

    export default {
        title: 'マイページ',
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
                number_unread_private_messages: null
            }
        },
        methods: {
            async fetchProjects () {
                const response = await axios.get('/api/mypage/')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.registered_projects = response.data.registered_projects
                this.applied_projects = response.data.applied_projects
                this.exchanged_public_messages = response.data.exchanged_public_messages
                this.exchanged_private_messages = response.data.exchanged_private_messages
                this.number_unread_private_messages = response.data.number_unread_private_messages
            },
            // ストアerror.jsにあるコードをクリアする
            clearError () {
                this.$store.commit('error/setCode', null)
            },
            toRegistered_projects () {
                this.$SmoothScroll(
                    document.querySelector('#registered_projects'),
                    400,
                    null,
                    null,
                    'y'
                )
            },
            toApplied_projects () {
                this.$SmoothScroll(
                    document.querySelector('#applied_projects'),
                    400,
                    null,
                    null,
                    'y'
                )
            },
            toComment () {
                this.$SmoothScroll(
                    document.querySelector('#comment'),
                    400,
                    null,
                    null,
                    'y'
                )
            },
            toMessage () {
                this.$SmoothScroll(
                    document.querySelector('#message'),
                    400,
                    null,
                    null,
                    'y'
                )
            },
            scrollTop () {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
        },
        computed: {
            // 未読のメッセージがあるかどうか
            isUnreadMessage () {
                return this.number_unread_private_messages >= 1
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
