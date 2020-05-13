<template>
    <section class="p-projectDetail">
        <h1 class="l-container__title">メッセージ詳細</h1>
        <div class="p-projectDetail__body">
        <dl class="c-dl">
            <dt>案件名</dt><dd>{{ project.title }}</dd>
            <dt>タイプ</dt><dd>{{ type }}</dd>
            <div v-if="isOneOff">
                <dt>下限金額</dt><dd>{{ project.minimum_amount }}</dd>
                <dt>上限金額</dt><dd>{{ project.max_amount }}</dd>
            </div>
            <dt>詳細</dt><dd>{{ project.detail }}</dd>
        </dl>
        <ul>
            <li v-for="private_message in private_messages" v-bind="private_message.id" class="p-message">
                <div class="p-message__author">
                    <img :src="private_message.author.icon_path" alt="アイコン画像"  height="30" class="imgIcon">
                    {{ private_message.author.name }}
                </div>
                <div class="p-message__content">
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
            <input type="text" class="c-input p-projectDetail__textarea" v-model="private_message_content" placeholder="メッセージを入力">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">送信する</button>
        </form>

        </div>
    </section>
</template>
<script>
    import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

    export default {
        data () {
            return {
                id: this.$route.params.id,
                project: [],
                private_message_content: '',
                private_messages: [],
                private_message_errors: null
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
            }
        },
        created() {
            this.clearError()
        },
        computed: {
            // notOwner () {
            //     return this.$store.getters['auth/userid'] !== this.project.owner.id
            // },
            // isRecruiting () {
            //     return this.project.status === 1
            // },
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
