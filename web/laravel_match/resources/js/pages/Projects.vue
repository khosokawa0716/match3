<template>
    <div>
        <h1>案件一覧</h1>
        <div>
            <Project
                class=""
                v-for="project in projects"
                :key="project.id"
                :item="project"
                />
        </div>
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
                projects: []
            }
        },
        methods: {
            async fetchProjects () {
                const response = await axios.get('/projects/list')

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
