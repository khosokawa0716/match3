<template>
    <footer class="l-footer">
<!--        <p class="l-footer__large">-->
<!--            <RouterLink to="/agreement">ご利用規約</RouterLink>-->
<!--        </p>-->
        <p class="l-footer__large">
            Copyright &copy; One-Stop Koshigoe LLC. All Rights Reserved
            <a
                :href=twitter
                class="u-twitter-icon" target="_blank">
                <i class="fab fa-twitter-square fa-2x"></i>
            </a>
        </p>
        <p class="l-footer__small">
            Copyright &copy; One-Stop Koshigoe
            <a
                :href=twitter
                class="u-twitter-icon" target="_blank">
                <i class="fab fa-twitter-square fa-2x"></i>
            </a>
        </p>
    </footer>
</template>
<script>
    export default {
        computed: {
            twitter () {
                return 'https://twitter.com/intent/tweet?text=【match】〜誰でもかんたんにWEBのお仕事を依頼、受注！！%0a &hashtags=match%0a&url=' + location.origin
            }
        }
    }
</script>
