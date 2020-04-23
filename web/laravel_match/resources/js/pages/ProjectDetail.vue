<template>
    <div>
        <h1>案件詳細画面</h1>
        <h5>案件の内容</h5>
        <dl>
            <dt>依頼した人</dt><dd>{{ project.owner.name }}</dd>
            <dt>案件名</dt><dd>{{ project.title }}</dd>
            <dt>タイプ</dt><dd>{{ project.type }}</dd>
            <dt>下限金額</dt><dd>{{ project.minimum_amount }}</dd>
            <dt>上限金額</dt><dd>{{ project.max_amount }}</dd>
            <dt>詳細</dt><dd>{{ project.detail }}</dd>
        </dl>
        <form class="form" @submit.prevent="publicMessageRegister">
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
    import { OK } from '../util'

    export default {
        props: {
            id: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                public_message_content: '',
                project: null,
                public_messages: []
            }
        },
        methods: {
            async fetchProjectDetail () {
                const response = await axios.get(`/project/detail/${this.id}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.project = response.data.project
                this.public_messages = response.data.public_messages
            },
            async publicMessageRegister () {
                const response = await axios.post(`/project/detail/${this.id}`, {
                    content: this.public_message_content
                })

                this.public_message_content = ''
                const response2 = await axios.get(`/project/detail/${this.id}`)
                this.public_messages = response2.data.public_messages
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchProjectDetail()
                    // await this.publicMessageRegister()
                },
                immediate: true
            }
        }
    }
</script>
