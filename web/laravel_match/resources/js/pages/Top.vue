<template>
    <section class="l-container p-top">
        <h1 class="p-top__title__l">エンジニア案件専用アプリ。<br>match</h1>
        <img src="/storage/pixta_48826839_M.jpg" alt="トップバナー">
        <div class="l-container__body">
            <div class="p-top__list">
                <h3 class="p-top__title">matchの特徴</h3>
                <ul>
                    <li>エンジニア案件専用だから、発注が簡単</li>
                    <li>お仕事の依頼は、手数料がかかりません</li>
                    <li>さまざまな強みをもったエンジニアが在籍</li>
                </ul>
            </div>
        </div>
        <form  class="p-top__form" @submit.prevent="toRegister">
            <div class="p-top__btn-wrapper">
                <button type="submit" class="c-btn c-btn__corp p-top__btn">まずは無料でユーザー登録</button>
            </div>
        </form>
        <div class="l-container__body">
            <div class="p-top__list">
                <h3 class="p-top__title">エンジニア案件に特化!<br>だから簡単</h3>
                <ol>
                    <li>ユーザー登録</li>
                    <li>タイトル、金額、詳細、を入力して案件登録</li>
                    <li>エンジニアから応募がきたら、直接やりとり</li>
                </ol>
                <p>※お仕事の依頼は、手数料がかかりません。</p>
                <p>※Twitterで、自分が発注した案件をフォロワーに宣伝することができます。</p>
            </div>
        </div>
        <form class="p-top__form" @submit.prevent="toRegister">
            <div class="p-top__btn-wrapper">
                <button type="submit" class="c-btn c-btn__corp p-top__btn">まずは無料でユーザー登録</button>
            </div>
        </form>
        <div class="l-container__body">
            <div class="p-top__list">
                <h3  class="p-top__title">エンジニアの方へ。<br>こんなお悩みありませんか</h3>
                <ul>
                    <li>大手のサイトでは、いろいろな種類の案件があり探しづらい</li>
                    <li>ユーザー登録に必要な項目が多く面倒</li>
                    <li>コンペのシステムで、苦労して提案したのに依頼者と全くやりとりできない</li>
                </ul>
            </div>
            <div class="p-top__list">
                <h3 class="p-top__title">簡単に依頼者とやりとりできます。</h3>
                <ol>
                    <li>ユーザー登録</li>
                    <li>案件を探して応募</li>
                    <li>依頼者の方と直接やりとり</li>
                </ol>
                <p>※応募は、期間限定で無料です。</p>
            </div>
        </div>
        <form class="p-top__form" @submit.prevent="toRegister">
            <div class="p-top__btn-wrapper">
                <button type="submit" class="c-btn c-btn__corp p-top__btn">まずは無料でユーザー登録</button>
            </div>
        </form>
        <form  class="p-top__form" @submit.prevent="toProjects">
            <div class="p-top__btn-wrapper">
                <button type="submit" class="c-btn c-btn__corp p-top__btn">まずは案件を見る</button>
            </div>
        </form>
    </section>
</template>
<script>
    export default {
        title: 'エンジニア案件専用アプリ',
        methods: {
            // ユーザー登録の画面に遷移する
            toRegister () {
                this.$router.push('/register')
            },

            // 案件一覧の画面に遷移する
            toProjects () {
                this.$router.push('/projects/list')
            },
        }
    }
</script>
