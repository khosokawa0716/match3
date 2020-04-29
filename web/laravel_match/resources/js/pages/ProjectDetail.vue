<template>
    <div>
        <h1>案件詳細画面</h1>
        <dl>
            <dt>依頼した人</dt><dd>{{ project.owner.name }}</dd>
            <dt>案件名</dt><dd>{{ project.title }}</dd>
            <dt>タイプ</dt><dd>{{ type }}</dd>
            <div v-if="isOneOff">
            <dt>下限金額</dt><dd>{{ project.minimum_amount }}</dd>
            <dt>上限金額</dt><dd>{{ project.max_amount }}</dd>
            </div>
            <dt>詳細</dt><dd>{{ project.detail }}</dd>
        </dl>
        <form class="form" @submit.prevent="apply" v-if="isRecruiting && notOwner">
<!--        ProjectDetailController.phpの例外処理を確認するときは下の行を有効にする-->
<!--        <form class="form" @submit.prevent="apply">-->
            <div class="form_button">
                <button type="submit" class="button button--inverse">この案件に応募する</button>
            </div>
        </form>
        <form class="form" @submit.prevent="publicMessageRegister">
            <div v-if="public_message_errors" class="errors">
                <ul v-if="public_message_errors.content">
                    <li v-for="msg in public_message_errors.content" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <textarea class="form__item" v-model="public_message_content"></textarea>
            <div class="form__button">
                <button type="submit" class="button button--inverse">メッセージを送信する</button>
            </div>
        </form>
        <h5>この案件に関するやりとり</h5>
        <ul>
            <li v-for="public_message in public_messages" v-bind="public_message.id">
                {{ public_message.author.name }}
                {{ public_message.content }}
                {{ public_message.created_at }}
            </li>
        </ul>
    </div>

</template>
<script>
    import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

    export default {
        props: {
            id: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                project: null,
                public_message_content: '',
                public_messages: [],
                public_message_errors: null
            }
        },
        methods: {
            async fetchProjectDetail () {
                const response = await axios.get(`/api/project/detail/${this.id}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.project = response.data.project
                this.public_messages = response.data.public_messages
            },
            async publicMessageRegister () {
                const response = await axios.post(`/api/project/detail/${this.id}`, {
                    content: this.public_message_content
                })

                // バリデーションエラー
                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.public_message_errors = response.data.errors
                    return false
                }

                // POST成功
                // テキスト入力部分を空にする
                this.public_message_content = ''
                // エラーメッセージをクリア
                this.public_message_errors = null
                // メッセージを全件取得して、再表示
                const response2 = await axios.get(`/api/project/detail/${this.id}`)
                this.public_messages = response2.data.public_messages

                // その他のエラー
                if (response.status !== CREATED) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
            },
            async apply () {
                const response = await axios.put(`/api/project/detail/${this.id}`, this.id)

                if (response.status === OK) {
                    // updateアクションが成功だった場合、ストアにメッセージを格納する
                    this.$store.commit('message/setContent', {
                        content: '案件に応募しました！',
                        timeout: 5000
                    })

                    // マイページに移動する
                    this.$router.push('/mypage')
                }

                // エラー
                if (response.status !== OK) {
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
            notOwner () {
                return this.$store.getters['auth/userid'] !== this.project.owner.id
            },
            isRecruiting () {
                return this.project.status === 1
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
