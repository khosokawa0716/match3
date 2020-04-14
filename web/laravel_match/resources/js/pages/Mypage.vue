<template>
    <div>
        <h1 class="l-container__title">マイページ</h1>
        <RouterLink class="button button--link" :to="{name: 'edit', params: {userId: 1}}">
            登録情報
        </RouterLink>
    </div>
</template>
