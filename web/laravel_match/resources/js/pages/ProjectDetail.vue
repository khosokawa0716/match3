<template>
    <section class="p-projectDetail">
        <h1 class="l-container__title">案件詳細</h1>
        <div class="p-projectDetail__body">
        <dl class="c-dl">
            <dt>依頼した人</dt><dd>{{ project.owner.name }}</dd>
            <dt>案件名</dt><dd>{{ project.title }}</dd>
            <dt>タイプ</dt><dd>{{ type }}</dd>
            <dt v-if="isOneOff">金額</dt><dd v-if="isOneOff">{{ project.minimum_amount }}円 〜 {{ project.max_amount }}円</dd>
            <dt>詳細</dt><dd>{{ project.detail }}</dd>
        </dl>
            <h5 class="l-container__subtitle">コメント</h5>
            <ul>
                <li v-for="public_message in public_messages" v-bind="public_message.id" class="p-message">
                    <div class="p-message__author">
                        <img :src="public_message.author.icon_path" alt="アイコン画像"  height="30" class="imgIcon">
                        {{ public_message.author.name }}
                    </div>
                    <div class="p-message__content">
                        {{ public_message.content }}
                    </div>
                    <div class="p-message__date">
                        {{ public_message.created_at }}
                    </div>
                </li>
            </ul>
        <form class="c-form" @submit.prevent="apply" v-if="isRecruiting && notOwner">
<!--        ProjectDetailController.phpの例外処理を確認するときは下の行を有効にする-->
<!--        <form class="c-form" @submit.prevent="apply">-->
            <button type="submit" class="c-btn c-btn__corp c-btn__l">この案件に応募する</button>
        </form>
        <form class="p-projectDetail__form" @submit.prevent="publicMessageRegister">
            <div v-if="public_message_errors" class="c-error">
                <ul v-if="public_message_errors.content">
                    <li v-for="msg in public_message_errors.content" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <textarea cols="30" rows="10" type="text" class="c-input p-projectDetail__textarea" v-model="public_message_content" placeholder="メッセージを入力"></textarea>
            <button type="submit" class="c-btn c-btn__corp c-btn__l">送信する</button>
        </form>

        </div>
    </section>

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
