<template>
    <section class="l-container">
        <h1 class="l-container__title">ページの有効期限切れ</h1>
        <div class="l-container__body">
            <p>ページの有効期限が切れました。</p>
            <p>このアプリケーションは、セキュリティのため30分以上操作がない場合ページが無効になります。</p>
            <p>お手数ですが、
                <RouterLink to="/login">ログイン</RouterLink>
                から操作をやり直してください。</p>
            <p>ログインせずにご使用になられていた場合には、
                <RouterLink to="/">トップページ</RouterLink>
                から操作をやり直してください。
            </p>
        </div>
    </section>
</template>
<script>
    export default {
        title: 'ページの有効期限切れ',
    }
</script>
