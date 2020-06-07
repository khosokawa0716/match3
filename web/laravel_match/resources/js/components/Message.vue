<template>
    <section class="l-message" v-show="message">{{ message }}</section>
</template>

<script>
    export default {
        computed: {
            message: function() {
                return this.$store.state.message.content
            }
        }
    }
</script>
