<template>
    <div>
        <h1 class="l-container__title">案件登録</h1>
        <form class="form" @submit.prevent="projectsRegister">
            <div v-if="registerErrors" class="errors">
                <ul v-if="registerErrors.title">
                    <li v-for="msg in registerErrors.title" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.type">
                    <li v-for="msg in registerErrors.type" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.minimum_amount">
                    <li v-for="msg in registerErrors.minimum_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.max_amount">
                    <li v-for="msg in registerErrors.max_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.detail">
                    <li v-for="msg in registerErrors.detail" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <label for="title">タイトル</label>
            <input type="text" class="form__item" id="title" v-model="projectsRegisterForm.title">
            <input type="radio" id="one-off" value="one-off" v-model="projectsRegisterForm.type">
            <label for="one-off">決まった金額を支払う</label>
            <input type="radio" id="service" value="service" v-model="projectsRegisterForm.type">
            <label for="service">サービス開始後の売り上げを分け合う</label>
            <div v-if="isOneOff">
                <label for="minimum-amount">下限金額</label>
                <input type="number" class="form__item" id="minimum-amount" max="10000000" v-model="projectsRegisterForm.minimum_amount">
                <label for="max-amount">上限金額</label>
                <input type="number" class="form__item" id="max-amount" max="10000000" v-model="projectsRegisterForm.max_amount">
            </div>
            <label for="detail">詳細</label>
            <input type="text" class="form__item" id="detail" v-model="projectsRegisterForm.detail">
            <div class="form__button">
                <button type="submit" class="button button--inverse">案件を登録する</button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                projectsRegisterForm: {
                    title: '',
                    type: 'one-off',
                    minimum_amount: '',
                    max_amount: '',
                    detail: ''
                }
            }
        },
        methods: {
            async projectsRegister () {
                console.log(this.projectsRegisterForm)
                // projectストアのregisterアクションを呼び出す
                await this.$store.dispatch('project/register', this.projectsRegisterForm)

                if (this.apiStatus) {
                    // registerアクションが成功だった場合、マイページに移動する
                    this.$router.push('/mypage')
                }
            }
        },
        computed: {
            apiStatus () {
                return this.$store.state.project.apiStatus
            },
            registerErrors () {
                return this.$store.state.project.registerErrorMessages
            },
            isOneOff () {
                if (this.projectsRegisterForm.type === 'one-off'){
                    return true
                } else {
                    return false
                }
            }
        }
    }
</script>
