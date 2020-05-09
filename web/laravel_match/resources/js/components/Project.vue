<template>
    <div class="">
        <ul>
            <li><span class="c-panel__title">{{ item.title }}</span></li>
            <li>{{ status }}</li>
            <li>{{ type }}</li>
            <li v-if="item.type === 'one-off'">{{ item.minimum_amount }}円 〜 {{ item.max_amount }}円</li>
            <li>
                <span class="c-panel__link">
                <RouterLink
                    v-if="isLogin"
                    :to="{name: 'projectDetail', params: { id: this.item.id }}"
                >
                    詳細を見る
                </RouterLink>
                <RouterLink
                    v-if="isLogin && isOwner && isRecruiting"
                    :to="{name: 'projectsEdit', params: { id: this.item.id }}"
                >
                    編集する
                </RouterLink>
                </span>
            </li>
        </ul>
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
