<template>
    <header class="l-header">
        <h1>
            <RouterLink class="l-header__title" to="/">match</RouterLink>
        </h1>
        <div class="p-menuTrigger" @click="toggleActiveStatus" :class="{'active': activeStatus}">
            <span class="p-menuTrigger__barger"></span>
            <span class="p-menuTrigger__barger"></span>
            <span class="p-menuTrigger__barger"></span>
        </div>
    <nav class="l-nav" :class="{'active': activeStatus}">
        <div v-if="isLogin">
        <ul class="c-menu">
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/projects/list">案件を探す</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/projects/register">案件を登録する</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/public_messages/list">コメント</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/private_messages/list">メッセージ</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus">
                <RouterLink class="c-menu__link" to="/mypage">
                    <img :src="icon_path" alt="アイコン画像"  height="20" class="imgIcon">
                    マイページ
                </RouterLink>
            </li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" :to="{name: 'edit', params: { id: userid }}">本人情報</RouterLink></li>
            <li class="c-menu__item">
                <button class="c-btn c-btn__corp c-btn__logout" @click="logout">ログアウト</button>
            </li>
        </ul>
        </div>
        <div v-else>
        <ul class="c-menu">
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/projects/list">案件を見る</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/login">ログイン</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/register">ユーザー登録</RouterLink></li>
        </ul>
        </div>
    </nav>
    </header>
</template>
<script>
    export default {
        data () {
            return {
                activeStatus: false
            }
        },
        methods: {
            // スマホのハンバーガーメニューの開閉を切り替える
            toggleActiveStatus () {
                this.activeStatus = !this.activeStatus
            },

            // ログアウト
            async logout () {
                this.activeStatus = !this.activeStatus // メニューを閉じる
                await this.$store.dispatch('auth/logout')

                if (this.apiStatus) {
                    this.$router.push('/login') // 処理が成功したら、ログイン画面に遷移する
                }
            }
        },
        computed: {
            // ストアでの処理が成功したかどうか
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },

            // ログインしているかどうか
            isLogin () {
                return this.$store.getters['auth/check']
            },

            // ログイン中のユーザーidを返す
            userid () {
                return this.$store.getters['auth/userid']
            },

            // ユーザーのアイコン画像のパスを返す
            icon_path () {
                return this.$store.getters['auth/icon_path']
            }
        },
        mounted() {
            // 画面トップへスクロールする
            document.onscroll = (e) => {
                this.position = document.documentElement.scrollTop || document.body.scrollTop;
            }
        }
    }
</script>
