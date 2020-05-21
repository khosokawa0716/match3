<template>
    <section class="p-projectDetail">
        <h1 class="l-container__title">メッセージ詳細</h1>
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
            <div v-if="notApplicant"> <!-- 自分の案件には、名前と自己症紹介は表示しない -->
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
    import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

    export default {
        title: 'メッセージ詳細 - ',
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
            async fetchPrivateMessages () {
                const response = await axios.get(`/api/private_messages/detail/${this.id}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.project = response.data.project

                this.owner.id = response.data.project.owner.id
                this.owner.name = response.data.project.owner.name
                this.owner.icon_path = response.data.project.owner.icon_path
                this.owner.profile_fields = response.data.project.owner.profile_fields

                this.applicant.id = response.data.project.applicant.id
                this.applicant.name = response.data.project.applicant.name
                this.applicant.icon_path = response.data.project.applicant.icon_path
                this.applicant.profile_fields = response.data.project.applicant.profile_fields

                this.private_messages = response.data.private_messages
            },
            async privateMessageRegister () {
                const response = await axios.post(`/api/private_messages/detail/${this.id}`, {
                    content: this.private_message_content
                })

                // バリデーションエラー
                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.private_message_errors = response.data.errors
                    return false
                }

                // POST成功
                // テキスト入力部分を空にする
                this.private_message_content = ''
                // エラーメッセージをクリア
                this.private_message_errors = null
                // メッセージを全件取得して、再表示
                const response2 = await axios.get(`/api/private_messages/detail/${this.id}`)
                this.private_messages = response2.data.private_messages

                // その他のエラー
                if (response.status !== CREATED) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
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
            notApplicant () {
                return this.$store.getters['auth/userid'] !== this.applicant.id
            },
            userid () {
                return this.$store.getters['auth/userid']
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
                    await this.fetchPrivateMessages()
                },
                immediate: true
            }
        }
    }
</script>
