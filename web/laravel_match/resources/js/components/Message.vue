<template>
<!--    <section class="l-message" v-show="message">-->
        <section class="l-message">
            テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
        {{ message }}
    </section>
</template>

<script>
    export default {
        computed: {
            message () {
                return this.$store.state.message.content
            }
        }
    }
</script>
