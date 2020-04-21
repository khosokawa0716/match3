<template>
    <div>
        <h1 class="l-container__title">マイページ</h1>
        <RouterLink class="button button--link" to="/projects/list">
            案件の一覧を見る
        </RouterLink>
        <RouterLink class="button button--link" to="/projects/register">
            案件を登録する
        </RouterLink>
        <RouterLink class="button button--link" :to="{name: 'projectsEdit', params: { projectId: pid }}">
            今登録した案件の編集
        </RouterLink>
        <RouterLink class="button button--link" :to="{name: 'edit', params: { userId: id }}">
            お客様の登録情報
        </RouterLink>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                id: this.$store.getters['auth/userid'],

                // いったんprojectのストア管理をやめてみる
                pid: this.$store.getters['project/projectid']
            }
        }
    }
</script>
