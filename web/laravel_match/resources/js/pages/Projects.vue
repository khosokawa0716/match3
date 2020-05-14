<template>
    <section class="l-container">
        <h1 class="l-container__title">案件一覧</h1>
        <div class="l-container__body">
        <h5 class="l-container__subtitle">タイプを絞り込む</h5>
        <label v-for="label in options" class="c-panel__radio">
            <input type="radio"
                   v-model="current"
                   v-bind:value="label.value">{{ label.label }}
        </label>
            <div class="c-panel">
            <Project
                class="c-panel__item"
                v-for="project in filterType"
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
                projects: [],
                options: [
                    { value: -1, label: 'すべて' },
                    { value: 'one-off', label: '依頼のときに一定の金額を支払う' },
                    { value: 'service', label: 'サービス公開後の収益を分け合う' }
                ],
                current: -1,
                // options2: [
                //     { value: -1, label: 'すべて' },
                //     { value: 1,  label: '募集中' },
                //     { value: 0,  label: '募集終了' }
                // ],
                // current2: -1
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
        // filters: { // currentの値が未定義となりエラー
        //     filterType: function (el) {
        //         return this.current < 0 ? true : this.current === el.type
        //     },
        //     filterStatus: function(el) {
        //         return this.current2 < 0 ? true : this.current2 === el.status
        //     }
        // },
        computed: {
            filterType () {
                return this.projects.filter(function(el) {
                    return this.current < 0 ? true : this.current === el.type
                }, this)
            },
            // filterStatus () {
            //     return this.projects.filter(function(el) {
            //         return this.current2 < 0 ? true : this.current2 === el.status
            //     }, this)
            // }
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
