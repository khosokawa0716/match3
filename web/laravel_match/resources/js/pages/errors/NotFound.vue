<template>
    <section class="l-container">
        <h1 class="l-container__title">ページが見つかりません</h1>
        <div class="l-container__body">
            <p>お探しのページは見つかりませんでした。</p>
            <p>URLに誤りがあるか、お探しのページは移動もしくは削除された可能性があります。</p>
            <p>お手数ですが、
                <RouterLink to="/">トップページ</RouterLink>
                から操作をやり直してください。</p>
        </div>
    </section>
</template>
<script>
    export default {
        title: 'ページが見つかりません',
    }
</script>
