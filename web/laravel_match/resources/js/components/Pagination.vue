<template>
    <div class="c-pagination">
        <RouterLink
            v-if="! isFirstPage"
            :to="`/projects/list?type=${selectType}&page=${currentPage - 1}`"
            class=""
            >&laquo; 前のページ</RouterLink>
        <RouterLink
            v-if="! isLastPage"
            :to="`/projects/list?type=${selectType}&page=${currentPage + 1}`"
            class=""
        >次のページ &raquo;</RouterLink>
    </div>
</template>
<script>
    export default {
        props: {
            currentPage: {
                type: Number,
                required: true
            },
            lastPage: {
                type: Number,
                required: true
            },
            selectType: {
                type: String,
                required: true
            }
        },
        computed: {
            isFirstPage () {
                return this.currentPage === 1
            },
            isLastPage () {
                return this.currentPage === this.lastPage
            }
        }
    }
</script>
