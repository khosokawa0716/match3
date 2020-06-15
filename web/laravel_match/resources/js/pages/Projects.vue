<template>
    <section>
        <div class="c-breadcrumb">
            <breadcrumb :breadcrumbs="breadcrumbs" />
        </div>
        <div class="l-container">
        <h1 class="l-container__title">案件一覧</h1>
        <div class="l-container__body">
            <p class="p-info" v-if="! isLogin">ログイン、ユーザー登録をすると案件の詳細を確認できます。</p>
        </div>
        <div class="p-filter">
            <div class="p-filter__item">
                <h5><span>状態で絞り込む</span></h5>
                    <input type="radio" id="1" v-model="selectStatus" value="1">
                    <label for="1">募集中</label>
                    <br>
                    <input type="radio" id="0" v-model="selectStatus" value="0">
                    <label for="0">募集終了</label>
                    <br>
                    <input type="radio" id="2" v-model="selectStatus" value="2">
                    <label for="2">すべて</label>
            </div>
            <div class="p-filter__item">
                <h5><span>支払い方法のタイプで絞り込む</span></h5>
                <input type="radio" id="all" v-model="selectType" value="all">
                <label for="all">すべて</label>
                <br>
                <input type="radio" id="one-off" v-model="selectType" value="one-off">
                <label for="one-off">依頼のときに一定の金額を支払う</label>
                <br>
                <input type="radio" id="service" v-model="selectType" value="service">
                <label for="service">サービス公開後の収益を分け合う</label>
            </div>
        </div>
        <div class="c-panel">
            <Project
                class="c-panel__item"
                v-for="project in projects"
                :key="project.id"
                :item="project"
                />
        </div>
        <div class="l-container__body">
            <Pagination :select-status="selectStatus" :select-type="selectType" :current-page="currentPage" :last-page="lastPage" />
        </div>
        </div>
    </section>
</template>
<script>
    import { OK } from '../util'
    import Project from '../components/Project.vue'
    import Pagination from '../components/Pagination.vue'
    import Breadcrumb from '../components/Breadcrumb'

    export default {
        components: {
            Breadcrumb,
            Project,
            Pagination
        },
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
        data () {
            return {
                breadcrumbs: [
                    {
                        name: 'ホーム',
                        path: '/'
                    },
                    {
                        name: '案件一覧'
                    }
                ],
                projects: [],
                selectStatus: this.status,
                selectType: this.type,
                currentPage: 0,
                lastPage: 0,
            }
        },
        methods: {
            // 案件をとってくる
            fetchProjects: async function () {
                // ProjectController@indexの起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.get(`/api/projects/list?status=${this.status}&type=${this.type}&page=${this.page}`)

                // エラーの処理
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 成功の場合
                this.projects = response.data.data
                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
                this.selectStatus = this.status
                this.selectType = this.type
            },

            // 絞り込みの条件が変更したときに、条件にあった案件を取ってくる
            fetchFilterProjects: function() {
                this.$router.push(`/projects/list?status=${this.selectStatus}&type=${this.selectType}&page=1`)
            }
        },
        computed: {
            // ログインしているかどうか
            isLogin: function() {
                return this.$store.getters["auth/check"]
            }
        },
        watch: {
            $route: {
                // 画面の表示のさいにfetchProjectsメソッドを実行する
                handler: async function () {
                    await this.fetchProjects()
                },
                immediate: true
            },

            // 募集しているかどうかの条件が変更になったさいにfetchFilterProjectsメソッドを実行する
            selectStatus: {
                handler: async function () {
                    await this.fetchFilterProjects()
                }
            },

            // 支払い方法タイプの条件が変更になったさいにfetchFilterProjectsメソッドを実行する
            selectType: {
                handler: async function () {
                    await this.fetchFilterProjects()
                }
            }
        }
    }
</script>
