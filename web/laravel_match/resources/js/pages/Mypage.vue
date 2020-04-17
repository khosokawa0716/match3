<template>
    <div>
        <h1 class="l-container__title">マイページ</h1>
        <RouterLink class="button button--link" to="/projects/register">
            案件を登録する
        </RouterLink>
        <RouterLink class="button button--link" :to="{name: 'edit', params: { userId: id }}">
            登録情報
        </RouterLink>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                id: this.$store.getters['auth/userid']
            }
        }
    }
</script>
