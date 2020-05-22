<template>
    <section class="l-container">
        <h1 class="l-container__title">案件一覧</h1>
        <div class="l-container__body">
            <p class="c-error" v-if="notLogin">ログイン、またはユーザー登録をおこなうと案件の詳細を確認できます。</p>
        <h5 class="l-container__subtitle">タイプを絞り込む</h5>
            <input type="radio" id="all" v-model="selectType" value="all">
            <label for="all">すべて</label>
            <br>
            <input type="radio" id="one-off" v-model="selectType" value="one-off">
            <label for="one-off">依頼のときに一定の金額を支払う</label>
            <br>
            <input type="radio" id="service" v-model="selectType" value="service">
            <label for="service">サービス公開後の収益を分け合う</label>
<!--        <label v-for="label in options" class="c-panel__radio">-->
<!--            <input type="radio"-->
<!--                   v-model="current"-->
<!--                   v-bind:value="label.value">{{ label.label }}-->
<!--        </label>-->
            <div class="c-panel">
            <Project
                class="c-panel__item"
                v-for="project in projects"
                :key="project.id"
                :item="project"
                />
            </div>
            <Pagination :select-type="selectType" :current-page="currentPage" :last-page="lastPage" />
        </div>
    </section>
</template>
<script>
    import { OK } from '../util'
    import Project from '../components/Project.vue'
    import Pagination from '../components/Pagination.vue'

    export default {
        title: '案件一覧',
        props: {
            page: {
                type: Number,
                required: false,
                default: 1
            },
            type: {
                type: String,
                required: false,
                default: 'all'
            }
        },
        components: {
            Project,
            Pagination
        },
        data () {
            return {
                projects: [],
                selectType: this.$route.query.type,
                // selectType: 'all',
                currentPage: 0,
                lastPage: 0,
                // options: [
                //     { value: -1, label: 'すべて' },
                //     { value: 'one-off', label: '依頼のときに一定の金額を支払う' },
                //     { value: 'service', label: 'サービス公開後の収益を分け合う' }
                // ],
                // current: -1,
            }
        },
        methods: {
            async fetchProjects () {
                console.log(this.page)
                console.log(this.type)
                // const response = await axios.get('/api/projects/list')
                const response = await axios.get(`/api/projects/list?type=${this.type}&page=${this.page}`)
                // const response = await axios.get(`/api/projects/list/?page=${this.page}`, this.type)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.projects = response.data.data
                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            async fetchFilterProjects () {
                const response = await axios.get(`/api/projects/list?type=${this.selectType}&page=1`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.projects = response.data.data
                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page

                // this.$route.params.type = this.selectType
                this.$router.push(`/projects/list?type=${this.selectType}&page=1`)
            }
        },
        computed: {
            filterType () {
                return this.projects.filter(function(el) {
                    return this.current < 0 ? true : this.current === el.type
                }, this)
            },
            notLogin () {
                return this.$store.getters["auth/check"] === false
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchProjects()
                },
                immediate: true
            },
            selectType: {
                async handler () {
                    console.log('fetchFilterProjectsメソッド起動')
                    await this.fetchFilterProjects()
                }
            }
        }
    }
</script>
