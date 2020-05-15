<template>
    <section class="l-message" v-show="message">{{ message }}</section>
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
