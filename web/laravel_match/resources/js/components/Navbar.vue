<template>
    <nav class="navbar">
        <RouterLink class="navbar__brand" to="/">
            match
        </RouterLink>
        <div class="navbar__menu">
            <div class="navbar__item">
                <button class="button">
                    <i class="icon ion-md-add"></i>
                    Submit a photo
                </button>
                <button class="button button--link">ログアウト</button>
            </div>
            <span class="navbar__item">
        username
      </span>
            <div class="navbar__item">
                <RouterLink class="button button--link" to="/login">
                    ログイン
                </RouterLink>
                <RouterLink class="button button--link" to="/register">
                    ユーザー登録
                </RouterLink>
            </div>
        </div>
    </nav>
</template>
