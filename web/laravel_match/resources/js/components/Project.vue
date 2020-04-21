<template>
    <div>
        <h5>{{ item.owner.name }}</h5>
        <p>{{ item.title }}</p>
        <p>{{ item.type }}</p>
        <p>{{ item.minimum_amount }}</p>
        <p>{{ item.max_amount }}</p>
    </div>
</template>
<script>
    export default {
        props: {
            item: {
                type: Object,
                required: true
            }
        }
    }
</script>
