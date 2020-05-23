<template>
    <section class="l-container">
        <h1 class="l-container__title">案件一覧</h1>
        <div class="l-container__body">
            <p class="c-error" v-if="notLogin">ログイン、またはユーザー登録をおこなうと案件の詳細を確認できます。</p>
        <h5 class="l-container__subtitle">状態</h5>
            <input type="radio" id="2" v-model="selectStatus" value="2">
            <label for="2">すべて</label>
            <br>
            <input type="radio" id="1" v-model="selectStatus" value="1">
            <label for="1">募集中</label>
            <br>
            <input type="radio" id="0" v-model="selectStatus" value="0">
            <label for="0">募集終了</label>
        <h5 class="l-container__subtitle">タイプ</h5>
            <input type="radio" id="all" v-model="selectType" value="all">
            <label for="all">すべて</label>
            <br>
            <input type="radio" id="one-off" v-model="selectType" value="one-off">
            <label for="one-off">依頼のときに一定の金額を支払う</label>
            <br>
            <input type="radio" id="service" v-model="selectType" value="service">
            <label for="service">サービス公開後の収益を分け合う</label>
            <div class="c-panel">
            <Project
                class="c-panel__item"
                v-for="project in projects"
                :key="project.id"
                :item="project"
                />
            </div>
            <Pagination :select-status="selectStatus" :select-type="selectType" :current-page="currentPage" :last-page="lastPage" />
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
            status: {
                type: String,
                required: false,
                default: "1"
            },
            type: {
                type: String,
                required: false,
                default: 'all'
            },
            page: {
                type: Number,
                required: false,
                default: 1
            }
        },
        components: {
            Project,
            Pagination
        },
        data () {
            return {
                projects: [],
                // selectStatus: this.$route.query.status,
                selectStatus: this.status,
                // selectType: this.$route.query.type,
                selectType: this.type,
                currentPage: 0,
                lastPage: 0,
            }
        },
        methods: {
            async fetchProjects () {
                console.log(this.status)
                console.log(this.type)
                const response = await axios.get(`/api/projects/list?status=${this.status}&type=${this.type}&page=${this.page}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.projects = response.data.data
                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            async fetchFilterProjects () {
                const response = await axios.get(`/api/projects/list?status=${this.selectStatus}&type=${this.selectType}&page=1`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.projects = response.data.data
                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page

                this.$router.push(`/projects/list?status=${this.selectStatus}&type=${this.selectType}&page=1`)
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
            selectStatus: {
                async handler () {
                    await this.fetchFilterProjects()
                }
            },
            selectType: {
                async handler () {
                    await this.fetchFilterProjects()
                }
            }
        }
    }
</script>
