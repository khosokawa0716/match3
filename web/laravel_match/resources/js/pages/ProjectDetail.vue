<template>
    <section class="p-projectDetail">
        <h1 class="l-container__title">案件詳細</h1>
        <div class="p-projectDetail__body">
        <dl class="c-dl">
            <div v-if="notOwner"> <!-- 自分の案件には、名前と自己症紹介は表示しない -->
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
            <dt>案件名</dt><dd>{{ project.title }}</dd>
            <dt>状態</dt><dd>{{ status }}</dd>
            <dt>タイプ</dt><dd>{{ type }}</dd>
            <dt v-if="isOneOff">金額</dt><dd v-if="isOneOff">{{ project.minimum_amount }}円 〜 {{ project.max_amount }}円</dd>
            <dt>詳細</dt><dd>{{ project.detail }}</dd>
        </dl>
            <form class="c-form" @submit.prevent="apply" v-if="isRecruiting && notOwner">
                <button type="submit" class="c-btn c-btn__corp c-btn__l">この案件に応募する</button>
            </form>
            <h5 class="l-container__subtitle">コメント</h5>
            <ul>
                <li v-for="public_message in public_messages" v-bind="public_message.id" class="p-message">
                    <div v-if="userid !== public_message.author.id">
                        <div class="p-message__author">
                            <img :src="public_message.author.icon_path" alt="アイコン画像"  height="20" class="imgIcon__detail">
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
    </section>

</template>
<script>
    import { OK, UNPROCESSABLE_ENTITY } from '../util'

    export default {
        title: '案件詳細',
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
                public_messages: [],
                public_message_content: '',
                public_message_errors: null,
                isActiveProfile: false
            }
        },
        methods: {
            async fetchProjectDetail () {
                const response = await axios.get(`/api/project/detail/${this.id}`)

                // エラーの処理
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // GETが成功したら、レスポンスをプロパティに代入する
                this.project = response.data.project
                this.owner.id = response.data.project.owner.id
                this.owner.name = response.data.project.owner.name
                this.owner.icon_path = response.data.project.owner.icon_path
                this.owner.profile_fields = response.data.project.owner.profile_fields
                this.public_messages = response.data.public_messages
            },
            async publicMessageRegister () {
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
            async apply () {
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
            },
            clearError () {
                this.$store.commit('error/setCode', null)
            },
            toggleProfile () {
                return this.isActiveProfile = !this.isActiveProfile
            }
        },
        created() {
            this.clearError()
        },
        computed: {
            notOwner () {
                return this.$store.getters['auth/userid'] !== this.owner.id
            },
            userid () {
                return this.$store.getters['auth/userid']
            },
            isRecruiting () {
                return this.project.status === 1
            },
            status () {
                if (this.project.status === 1) {
                    return '募集中'
                } else {
                    return '募集終了'
                }
            },
            type () {
                if (this.project.type === 'one-off') {
                    return '依頼のときに一定の金額を支払う'
                } else {
                    return 'サービス公開後の収益を分け合う'
                }
            },
            isOneOff () {
                return this.project.type === 'one-off';
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchProjectDetail()
                },
                immediate: true
            }
        }
    }
</script>
