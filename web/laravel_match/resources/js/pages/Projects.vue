<template>
    <div>
        <h1>案件一覧</h1>
        <h5>募集しているかどうかを絞り込む</h5>
        <label v-for="label in options">
            <input type="radio"
                   v-model="current"
                   v-bind:value="label.value">{{ label.label }}
        </label>
        <div>
            <Project
                class=""
                v-for="project in computedProjects"
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
                projects: [],
                options: [
                    { value: -1, label: 'すべて' },
                    { value: 1,  label: '募集中' },
                    { value: 0,  label: '募集終了' }
                ],
                // 選択している options の value を記憶するためのデータ
                // 初期値を「-1」つまり「すべて」にする
                current: -1
            }
        },
        methods: {
            async fetchProjects () {
                const response = await axios.get('/api/projects/list')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.projects = response.data.data
            }
        },
        computed: {
            computedProjects () {
                return this.projects.filter(function(el) {
                    return this.current < 0 ? true : this.current === el.status
                }, this)
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
