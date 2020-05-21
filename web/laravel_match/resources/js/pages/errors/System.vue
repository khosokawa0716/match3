<template>
    <section class="l-container">
        <h1 class="l-container__title">サーバーエラー</h1>
        <div class="l-container__body">
            <p>サーバーエラーが発生しました。</p>
            <p>サーバーの問題でお探しのページを表示できません。</p>
            <p>再度時間をおいて<RouterLink to="/">トップページ</RouterLink>
                から操作をやり直してください。</p>
            <p>時間をおいても、アクセスできない場合には以下メールアドレスまでご連絡ください。</p>
            <p>k.hosokawa0716@gmail.com</p>
        </div>
    </section>
</template>
<script>
    export default {
        title: 'サーバーエラー',
    }
</script>
