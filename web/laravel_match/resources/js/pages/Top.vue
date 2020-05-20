<template>
    <section class="l-container">
        <h1 class="l-container__title">エンジニア案件専用アプリ！　match</h1>
        <img src="/storage/top-baner.png" alt="トップバナー">
        <div class="l-container__body">
            <h3 class="p-top__title">matchの特徴</h3>
            <div>
                <ul>
                    <li>エンジニア案件専用だから、発注が簡単</li>
                    <li>お仕事の依頼に手数料無料</li>
                    <li>会話のできるエンジニアが多数在籍</li>
                </ul>
            </div>
        </div>
        <form  class="c-form" @submit.prevent="toRegister">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">まずは無料でユーザー登録</button>
        </form>
        <div class="l-container__body">
            <h3 class="p-top__title">システム案件、WEB案件に限定!だから簡単</h3>
            <h5>ご利用の流れ</h5>
            <ol>
                <li>会員登録</li>
                <li>たった3つの項目で、案件登録
                <ol>
                    <li>タイトル</li>
                    <li>金額</li>
                    <li>詳細</li>
                </ol>
                </li>
                <li>エンジニアから応募がきたら、直接やりとり</li>
            </ol>
            <p>※お仕事の依頼は、無料です。</p>
            <p>※Twitterで、自分が発注した案件をフォロワーに宣伝することができます。</p>
        </div>
        <form  class="c-form" @submit.prevent="toRegister">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">まずは無料でユーザー登録</button>
        </form>
        <div class="l-container__body">
            <h3  class="p-top__title">エンジニアの方へ。こんなお悩みありませんか</h3>
            <ul>
                <li>大手のサイトではいろいろな種類の案件があり探しづらい</li>
                <li>会員登録に必要な項目が多く面倒</li>
                <li>コンペのシステムで、苦労して提案したのに依頼者と全くやりとりできない</li>
            </ul>
            <h3>簡単に依頼者とやりとりできます。</h3>
            <h5>ご利用の流れ</h5>
            <ol>
                <li>会員登録</li>
                <li>案件を探して応募</li>
                <li>依頼者の方と直接やりとり</li>
            </ol>
            <p>※応募は、期間限定で無料です。</p>
        </div>
        <form  class="c-form" @submit.prevent="toRegister">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">まずは無料でユーザー登録</button>
        </form>
        <form  class="c-form" @submit.prevent="toProjects">
            <button type="submit" class="c-btn c-btn__corp c-btn__l">まずは案件を見る</button>
        </form>
    </section>
</template>
<script>
    export default {
        methods: {
            toRegister () {
                this.$router.push('/register')
            },
            toProjects () {
                this.$router.push('/projects/list')
            },
        }
    }
</script>
