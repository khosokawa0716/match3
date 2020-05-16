<template>
    <section class="l-container">
        <h1 class="l-container__title">ページへのアクセス権がありません。</h1>
        <div class="l-container__body">
            <p>指定されたURLへのアクセス権限がありません。</p>
            <p>案件に応募する操作をおこなった場合には、他のユーザーがすでに応募してしまった可能性があります。</p>
            <p>その他の操作の場合には、ログインするアカウントが間違っている可能性があります。</p>
            <p>お手数ですが、
                <RouterLink to="/">トップページ</RouterLink>
                から操作をやり直してください。</p>
            <p>繰り返しこのページになってしまう場合には、画面右上のログアウトを押してください。</p>
        </div>
    </section>
</template>
