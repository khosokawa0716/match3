<template>
    <section class="l-message" v-show="message">
        <span>
        {{ message }}
        </span>
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
