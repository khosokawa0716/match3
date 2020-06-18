<template>
    <ul class="c-breadcrumb__list">
        <li class="c-breadcrumb__item" v-for="(crumb,key) in breadcrumbs" :key="crumb.name">
            <span v-if="crumb.path"><RouterLink :to="crumb.path">{{ crumb.name }}</RouterLink></span>
            <p v-else>{{ crumb.name }}</p>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            breadcrumbs: {
                type: Array
            }
        }
    }
</script>
