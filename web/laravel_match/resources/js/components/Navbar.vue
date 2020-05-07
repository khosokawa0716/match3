<template>
    <header class="l-header">
        <h1><RouterLink class="l-header__title" to="/">match</RouterLink></h1>
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
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/public_messages/list">掲示板</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" to="/private_messages/list">メッセージ</RouterLink></li>
            <li class="c-menu__item" @click="toggleActiveStatus">
                <RouterLink class="c-menu__link" to="/mypage">
                    <img :src="icon_path" alt="アイコン画像"  height="30" class="imgIcon">
                    マイページ
                </RouterLink>
            </li>
            <li class="c-menu__item" @click="toggleActiveStatus"><RouterLink class="c-menu__link" :to="{name: 'edit', params: { id: userid }}">本人情報</RouterLink></li>
            <li class="c-menu__item"><button class="c-btn c-btn__corp c-btn__l" @click="logout">ログアウト</button></li>
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
            toggleActiveStatus () {
                this.activeStatus = !this.activeStatus
            },
            async logout () {
                this.activeStatus = !this.activeStatus
                await this.$store.dispatch('auth/logout')

                if (this.apiStatus) {
                    this.$router.push('/login')
                }
            }
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },
            isLogin () {
                return this.$store.getters['auth/check']
            },
            userid () {
                return this.$store.getters['auth/userid']
            },
            icon_path () {
                return this.$store.getters['auth/icon_path']
            }
        }
    }
</script>
