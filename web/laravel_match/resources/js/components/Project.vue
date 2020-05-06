<template>
    <div>
        <h5>登録した人: {{ item.owner.name }}</h5>
        <p>タイトル: {{ item.title }}</p>
        <p>状態: {{ status }}</p>
        <p>タイプ: {{ type }}</p>
        <p v-if="item.type === 'one-off'">下限金額: {{ item.minimum_amount }}円</p>
        <p v-if="item.type === 'one-off'">上限金額: {{ item.max_amount }}円</p>
    <form class="c-form" @submit.prevent="edit" v-if="isLogin && isOwner && isRecruiting">
<!--        ProjectController.phpの例外処理を確認するときは下の行を有効にする-->
<!--        <form class="form" @submit.prevent="edit">-->
        <button type="submit" class="c-btn c-btn__corp c-btn__l">編集する</button>
    </form>
    <form class="c-form" @submit.prevent="showDetail" v-if="isLogin">
        <button type="submit" class="c-btn c-btn__corp c-btn__l">詳細</button>
    </form>
    </div>
</template>
<script>
    export default {
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        methods: {
            async edit () {
                this.$router.push('/projects/' + this.item.id + '/edit')
            },
            async showDetail () {
                this.$router.push('/project/detail/' + this.item.id)
            },
            clearError () {
                this.$store.commit('error/setCode', null)
            }
        },
        created() {
            this.clearError()
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },
            isLogin () {
                return this.$store.getters['auth/check']
            },
            isOwner () {
                return this.$store.getters['auth/userid'] === this.item.owner.id
            },
            isRecruiting () {
                return this.item.status === 1
            },
            status () {
                if (this.item.status === 1) {
                    return '募集中'
                } else {
                    return '募集終了'
                }
            },
            type () {
                if (this.item.type === 'one-off') {
                    return '依頼のときに一定の金額を支払う'
                } else {
                    return 'サービス公開後の収益を分け合う'
                }
            }
        }
    }
</script>
