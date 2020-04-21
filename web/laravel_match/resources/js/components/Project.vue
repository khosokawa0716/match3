<template>
    <div>
<!--        <h5>{{ item.owner.name }}</h5>-->
        <p>案件ID: <span>{{ item.id }}</span></p>
        <p>{{ item.title }}</p>
        <p>{{ item.type }}</p>
        <p>{{ item.minimum_amount }}</p>
        <p>{{ item.max_amount }}</p>
<!--        <RouterLink-->
<!--            v-if="isLogin"-->
<!--            :to="`/projects/${item.id}/edit`"-->
<!--            >-->
<!--            編集する-->
<!--        </RouterLink>-->
        <form class="form" @submit.prevent="edit">
        <div class="form__button">
            <button type="submit" class="button button--inverse">編集する</button>
        </div>
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
                // console.log(this.item.id) // methodでidが使えることを確認した。
                // projectストアのeditアクションを呼び出す
                await this.$store.dispatch('project/edit', this.item.id)

                if (this.apiStatus) {
                    // editアクションが成功だった場合、案件編集に移動する
                    this.$router.push('/projects/' + this.item.id + '/edit')
                }
            }
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },
            isLogin () {
                return this.$store.getters['auth/check']
            }
        }
    }
</script>
