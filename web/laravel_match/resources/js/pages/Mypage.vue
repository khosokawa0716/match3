<template>
    <div>
        <h1 class="l-container__title">マイページ</h1>
        <h5>自分の登録した案件一覧</h5>
        <div>
            <Project
                class=""
                v-for="project in projects"
                :key="project.id"
                :item="project"
            />
        </div>
        <RouterLink class="button button--link" to="/projects/list">
            案件の一覧を見る
        </RouterLink>
        <RouterLink class="button button--link" to="/projects/register">
            案件を登録する
        </RouterLink>
        <RouterLink class="button button--link" :to="{name: 'edit', params: { userId: id }}">
            お客様の登録情報
        </RouterLink>
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
                projects: []
            }
        },
        methods: {
            async fetchProjects () {
                const response = await axios.get('/api/mypage')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.projects = response.data.data
            }
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
