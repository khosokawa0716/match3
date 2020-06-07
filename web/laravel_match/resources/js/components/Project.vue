<template>
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
                <a class="u-twitter-icon__project" :href=twitter target="_blank">
                    <i class="fab fa-twitter-square fa-2x"></i>
                </a>
                </span>
            </li>
        </ul>
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
            // ストアのエラーをクリアする
            clearError: function() {
                this.$store.commit('error/setCode', null)
            }
        },
        created: function() {
            // 表示のさいにストアのエラーをクリアする
            this.clearError()
        },
        computed: {
            // ストアでの処理が成功したかどうか
            apiStatus: function() {
                return this.$store.state.auth.apiStatus
            },

            // ログインしているかどうか
            isLogin: function() {
                return this.$store.getters['auth/check']
            },

            // その案件を登録したユーザーかどうか
            isOwner: function() {
                return this.$store.getters['auth/userid'] === this.item.owner.id
            },

            // その案件が募集中かどうか
            isRecruiting: function() {
                return this.item.status === 1
            },

            // 画面上での表示
            status: function() {
                if (this.item.status === 1) {
                    return '募集中'
                } else {
                    return '募集終了'
                }
            },

            // 画面上での表示
            type: function() {
                if (this.item.type === 'one-off') {
                    return '依頼のときに一定の金額を支払う'
                } else {
                    return 'サービス公開後の収益を分け合う'
                }
            },

            // Twitterアイコンを押したさいの動的なURLの生成
            twitter: function() {
                const url = this.$router.resolve({
                    name: 'projectsList'
                }).href

                // タイプによって金額を表示するかどうか
                if (this.item.type === 'one-off') {
                    return 'https://twitter.com/intent/tweet?text=【match】〜誰でもかんたんにWEBのお仕事を依頼、受注！！%0a' + this.item.title +
                        '%0a（' + this.item.minimum_amount + '円 〜 ' + this.item.max_amount + '円 ）' +
                        '%0a&hashtags=match%0a&url=' + location.origin + url
                } else {
                    return 'https://twitter.com/intent/tweet?text=【match】〜誰でもかんたんにWEBのお仕事を依頼、受注！！%0a' + this.item.title +
                        '%0a&hashtags=match%0a&url=' + location.origin + url
                }
            }
        }
    }
</script>
