<template>
    <section class="p-projectDetail">
        <h1 class="l-container__title">メッセージ詳細</h1>
        <div class="p-projectDetail__body">
        <dl class="c-dl">
            <div v-if="notOwner"> <!-- 自分が登録した案件には、名前と自己症紹介は表示しない -->
                <dt>依頼した人</dt>
                <dd @click="toggleProfile" class="cursorHelp">
                    <img :src="owner.icon_path" alt="アイコン画像"  height="20" class="imgIcon__detail">
                    {{ owner.name }}
                </dd>
                <dt v-if="isActiveProfile">自己紹介</dt>
                <dd v-if="isActiveProfile">
                    {{ owner.profile_fields }}
                </dd>
            </div>
            <div v-if="notApplicant"> <!-- 自分が応募した案件には、名前と自己症紹介は表示しない -->
                <dt>応募した人</dt>
                <dd @click="toggleProfile" class="cursorHelp">
                    <img :src="applicant.icon_path" alt="アイコン画像"  height="20" class="imgIcon__detail">
                    {{ applicant.name }}
                </dd>
                <dt v-if="isActiveProfile">自己紹介</dt>
                <dd v-if="isActiveProfile">
                    {{ applicant.profile_fields }}
                </dd>
            </div>
            <dt>案件名</dt><dd>{{ project.title }}</dd>
            <dt>タイプ</dt><dd>{{ type }}</dd>
            <dt v-if="isOneOff">金額</dt><dd v-if="isOneOff">{{ project.minimum_amount }}円 〜 {{ project.max_amount }}円</dd>
            <dt>詳細</dt><dd>{{ project.detail }}</dd>
        </dl>
        <ul>
            <li v-for="private_message in private_messages" v-bind="private_message.id" class="p-message">
                <div v-if="userid !== private_message.author.id">
                    <div class="p-message__author">
                        <img :src="private_message.author.icon_path" alt="アイコン画像"  height="20" class="imgIcon">
                        {{ private_message.author.name }}
                    </div>
                    <div class="p-message__content">
                        {{ private_message.content }}
                    </div>
                </div>
                <div v-else class="p-message__my-content">
                    {{ private_message.content }}
                </div>
                <div class="p-message__date">
                {{ private_message.created_at }}
                </div>
            </li>
        </ul>
        <form class="p-projectDetail__form" @submit.prevent="privateMessageRegister">
            <div v-if="private_message_errors" class="c-error">
                <ul v-if="private_message_errors.content">
                    <li v-for="msg in private_message_errors.content" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <textarea cols="30" rows="10" type="text" class="c-input p-projectDetail__textarea" v-model="private_message_content" placeholder="内容（200文字以内）"></textarea>
            <button type="submit" class="c-btn c-btn__corp p-projectDetail__btn">送信する</button>
        </form>

        </div>
    </section>
</template>
<script>
    import { OK, UNPROCESSABLE_ENTITY } from '../util'

    export default {
        title: 'メッセージ詳細',
        data () {
            return {
                id: this.$route.params.id,
                project: [],
                owner: {
                    id: '',
                    name: '',
                    icon_path: '',
                    profile_fields: ''
                },
                applicant: {
                    id: '',
                    name: '',
                    icon_path: '',
                    profile_fields: ''
                },
                private_messages: [],
                private_message_content: '',
                private_message_errors: null,
                isActiveProfile: false
            }
        },
        methods: {
            // メッセージ詳細画面に表示する案件やメッセージをとってくる
            fetchPrivateMessages: async function () {
                // PrivateMessagesDetailController@showを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.get(`/api/private_messages/detail/${this.id}`)
                console.dir(response)

                // エラーの場合
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 表示する情報をプロパティに代入する
                  // メッセージに紐づく案件
                this.project = response.data.project

                  // 案件を登録したユーザーの情報
                this.owner.id = response.data.project.owner.id
                this.owner.name = response.data.project.owner.name
                this.owner.icon_path = response.data.project.owner.icon_path
                this.owner.profile_fields = response.data.project.owner.profile_fields

                  // 案件に応募したユーザーの情報
                this.applicant.id = response.data.applicant.id
                this.applicant.name = response.data.applicant.name
                this.applicant.icon_path = response.data.applicant.icon_path
                this.applicant.profile_fields = response.data.applicant.profile_fields

                  // やりとしたメッセージ
                this.private_messages = response.data.private_messages
            },

            // メッセージを投稿する
            privateMessageRegister: async function () {
                // PrivateMessagesDetailController@createの起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.post(`/api/private_messages/detail/${this.id}`, {
                    content: this.private_message_content
                })

                console.log(this.id)

                // エラーの処理
                if (response.status === UNPROCESSABLE_ENTITY) { // バリデーションエラー
                    this.private_message_errors = response.data.errors
                    return false
                } else if (response.status !== OK) { // その他のエラー
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // POST成功の場合
                this.private_message_content = '' // テキスト入力部分を空にする
                this.private_message_errors = null // エラーメッセージをクリア
                const response2 = await axios.get(`/api/private_messages/detail/${this.id}`) // メッセージを全件取得
                this.private_messages = response2.data.private_messages // レスポンスをプロパティに代入
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

            // 案件に応募したユーザーでないときにtrueを返却
            notApplicant: function() {
                return this.$store.getters['auth/userid'] !== this.applicant.id
            },

            // ユーザーIDをストアからとってくる
            userid: function() {
                return this.$store.getters['auth/userid']
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
                // 画面の表示のさいにfetchPrivateMessagesメソッドを実行する
                handler: async function () {
                    await this.fetchPrivateMessages()
                },
                immediate: true
            }
        }
    }
</script>
