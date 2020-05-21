<template>
    <div class="c-pagination">
        <RouterLink
            v-if="! isFirstPage"
            :to="`/projects/list/?page=${currentPage - 1}`"
            class="c-btn"
            >&laquo; 前へ</RouterLink>
        <RouterLink
            v-if="! isLastPage"
            :to="`/projects/list/?page=${currentPage + 1}`"
            class="c-btn"
        >次へ &raquo;</RouterLink>
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
