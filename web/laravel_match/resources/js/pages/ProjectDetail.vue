<template>
    <section>
        <div class="c-breadcrumb">
            <breadcrumb :breadcrumbs="breadcrumbs" />
        </div>
        <div class="p-projectDetail">
        <h1 class="l-container__title">案件詳細</h1>
        <div class="p-projectDetail__body">
        <dl class="c-dl">
            <div v-if="notOwner"> <!-- 自分が登録した案件には、名前と自己症紹介は表示しない -->
                <dt class="c-dl__dt">依頼した人</dt>
                    <dd class="c-dl__dd u-cursorHelp" @click="toggleProfile">
                        <img :src="owner.icon_path" alt="アイコン画像"  height="20" class="u-imgIcon__detail">
                        {{ owner.name }}
                    </dd>
                <dt class="c-dl__dt" v-if="isActiveProfile">自己紹介</dt>
                    <dd class="c-dl__dd" v-if="isActiveProfile">
                        {{ owner.profile_fields }}
                    </dd>
            </div>
            <dt class="c-dl__dt">案件名</dt><dd class="c-dl__dd">{{ project.title }}</dd>
            <dt class="c-dl__dt">状態</dt><dd class="c-dl__dd">{{ status }}</dd>
            <dt class="c-dl__dt">タイプ</dt><dd class="c-dl__dd">{{ type }}</dd>
            <dt class="c-dl__dt" v-if="isOneOff">金額</dt><dd class="c-dl__dd" v-if="isOneOff">{{ project.minimum_amount }}円 〜 {{ project.max_amount }}円</dd>
            <dt class="c-dl__dt">詳細</dt><dd class="c-dl__dd">{{ project.detail }}</dd>
        </dl>
            <form class="c-form" @submit.prevent="apply" v-if="isRecruiting && notOwner && !isUserApplied">
<!--                応募のボタンが押せる条件は、1.募集中、2.案件登録者でない、3.すでに応募しているユーザーでない-->
                <button type="submit" class="c-btn c-btn__corp c-btn__l">この案件に応募する</button>
            </form>
            <h5 class="l-container__subtitle">コメント</h5>
            <ul>
                <li v-for="public_message in public_messages" v-bind="public_message.id" class="p-message">
                    <div v-if="userid !== public_message.author.id">
                        <div class="p-message__author">
                            <img :src="public_message.author.icon_path" alt="アイコン画像"  height="20" class="u-imgIcon__detail">
                            {{ public_message.author.name }}
                        </div>
                        <div class="p-message__content">
                            {{ public_message.content }}
                        </div>
                    </div>
                    <div v-else class="p-message__my-content">
                        {{ public_message.content }}
                    </div>
                    <div class="p-message__date">
                        {{ public_message.created_at }}
                    </div>
                </li>
            </ul>
        <form class="p-projectDetail__form" @submit.prevent="publicMessageRegister">
            <div v-if="public_message_errors" class="c-error">
                <ul v-if="public_message_errors.content">
                    <li v-for="msg in public_message_errors.content" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <textarea cols="30" rows="10" type="text" class="c-input p-projectDetail__textarea" v-model="public_message_content" placeholder="内容（200文字以内）"></textarea>
            <button type="submit" class="c-btn c-btn__corp p-projectDetail__btn">送信する</button>
        </form>

        </div>
        </div>
    </section>

</template>
<script>
    import { OK, UNPROCESSABLE_ENTITY } from '../util'
    import Breadcrumb from '../components/Breadcrumb'

    export default {
        components: {
            Breadcrumb
        },
        title: '案件詳細',
        data () {
            return {
                breadcrumbs: [
                    {
                        name: 'ホーム',
                        path: '/'
                    },
                    {
                        name: '案件一覧',
                        path: '/projects/list'
                    },
                    {
                        name: '案件詳細'
                    }
                ],
                id: this.$route.params.id, // プロジェクトのid
                isUserApplied: false,
                project: [],
                owner: {
                    id: '',
                    name: '',
                    icon_path: '',
                    profile_fields: ''
                },
                public_messages: [],
                public_message_content: '',
                public_message_errors: null,
                isActiveProfile: false
            }
        },
        methods: {
            // 案件詳細画面に表示する案件やコメントをとってくる
            fetchProjectDetail: async function () {
                // ProjectDetailController@showを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.get(`/api/project/detail/${this.id}`)

                // エラーの処理
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 表示する情報をプロパティに代入する
                  // 案件
                this.project = response.data.project

                  // 案件を登録したユーザーの情報
                this.owner.id = response.data.project.owner.id
                this.owner.name = response.data.project.owner.name
                this.owner.icon_path = response.data.project.owner.icon_path
                this.owner.profile_fields = response.data.project.owner.profile_fields

                  // コメント
                this.public_messages = response.data.public_messages

                  // ログインユーザーがすでに応募した案件かどうか
                this.isUserApplied = response.data.is_user_applied
            },

            // コメントを投稿する
            publicMessageRegister: async function () {
                // ProjectDetailController@createの起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.post(`/api/project/detail/${this.id}`, {
                    content: this.public_message_content
                })

                // エラーの処理
                if (response.status === UNPROCESSABLE_ENTITY) { // バリデーションエラー
                    this.public_message_errors = response.data.errors
                    return false
                } else if (response.status !== OK) { // その他のエラー
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // POST成功
                this.public_message_content = '' // テキスト入力部分を空にする
                this.public_message_errors = null // エラーメッセージをクリア
                const response2 = await axios.get(`/api/project/detail/${this.id}`) // メッセージを全件取得
                this.public_messages = response2.data.public_messages // レスポンスをプロパティに代入
            },

            // 案件に応募する
            apply: async function () {
                if (confirm('この案件に応募します。よろしいですか？')) {
                    // ProjectDetailController@updateの起動
                    // 返却されたオブジェクトをresponseに代入
                    const response = await axios.put(`/api/project/detail/${this.id}`, this.id)

                    // エラーの処理
                    if (response.status !== OK) {
                        this.$store.commit('error/setCode', response.status)
                        return false
                    }

                    // updateアクションが成功だった場合
                    this.$store.commit('message/setContent', {
                        content: '案件に応募しました！', // ストアにメッセージを格納する
                        timeout: 5000
                    })

                    // マイページに移動する
                    this.$router.push('/mypage')
                }
            },

            // ストアerror.jsにあるコードをクリアする
            clearError: function() {
                this.$store.commit('error/setCode', null)
            },

            // 自己紹介の表示、非表示を切り替える
            toggleProfile: function() {
                return this.isActiveProfile = !this.isActiveProfile
            }
        },
        created: function() {
            // 一度エラーが出た後、ブラウザバックなどで戻ってきたときにクリアする
            this.clearError()
        },
        computed: {
            // 案件を登録したユーザーでないときにtrueを返却
            notOwner: function() {
                return this.$store.getters['auth/userid'] !== this.owner.id
            },

            // ユーザーIDをストアからとってくる
            userid: function() {
                return this.$store.getters['auth/userid']
            },

            // 案件が募集中かどうか
            isRecruiting: function() {
                return this.project.status === 1
            },

            // 画面上での表示
            status: function() {
                if (this.project.status === 1) {
                    return '募集中'
                } else {
                    return '募集終了'
                }
            },

            // 画面上での表示
            type: function() {
                if (this.project.type === 'one-off') {
                    return '依頼のときに一定の金額を支払う'
                } else {
                    return 'サービス公開後の収益を分け合う'
                }
            },

            // 案件のタイプが「依頼のときに一定の金額を支払う」かどうか
            isOneOff: function() {
                return this.project.type === 'one-off';
            }
        },
        watch: {
            $route: {
                // 画面の表示のさいにfetchProjectDetailメソッドを実行する
                handler: async function () {
                    await this.fetchProjectDetail()
                },
                immediate: true
            }
        }
    }
</script>
