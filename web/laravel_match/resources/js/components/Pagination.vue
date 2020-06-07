<template>
    <div class="c-pagination">
        <RouterLink
            v-if="! isFirstPage"
            :to="`/projects/list?status=${this.selectStatus}&type=${selectType}&page=${currentPage - 1}`"
            class=""
            >&laquo; 前のページ</RouterLink>
        <RouterLink
            v-if="! isLastPage"
            :to="`/projects/list?status=${this.selectStatus}&type=${selectType}&page=${currentPage + 1}`"
            class=""
        >次のページ &raquo;</RouterLink>
    </div>
</template>
<script>
    export default {
        props: { // Projects.vue から以下4つのパラメータを受け取る
            // 1.絞り込み条件_案件が募集中かどうか
            selectStatus: {
                type: String,
                required: true
            },
            // 2.絞り込み条件_案件のタイプ
            selectType: {
                type: String,
                required: true
            },
            // 3.現在のページ
            currentPage: {
                type: Number,
                required: true
            },
            // 4.最後のページ
            lastPage: {
                type: Number,
                required: true
            }
        },
        computed: {
            // 始めのページかどうか
            isFirstPage: function() {
                return this.currentPage === 1
            },
            // 最後のページかどうか
            isLastPage: function() {
                return this.currentPage === this.lastPage
            }
        }
    }
</script>
