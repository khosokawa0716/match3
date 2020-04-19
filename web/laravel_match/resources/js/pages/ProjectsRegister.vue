<template>
    <div>
        <h1 class="l-container__title">案件登録</h1>
        <form class="form" @submit.prevent="projectsRegister">
            <label for="title">タイトル</label>
            <input type="text" class="form__item" id="title" v-model="projectsRegisterForm.title">
            <input type="radio" id="one-off" value="one-off" v-model="projectsRegisterForm.type">
            <label for="one-off">決まった金額を支払う</label>
            <input type="radio" id="service" value="service" v-model="projectsRegisterForm.type">
            <label for="service">サービス開始後の売り上げを分け合う</label>
            <label for="minimum-amount">下限金額</label>
            <input type="number" class="form__item" id="minimum-amount" max="10000000" v-model="projectsRegisterForm.minmum_amount">
            <label for="max-amount">上限金額</label>
            <input type="number" class="form__item" id="max-amount" max="10000000" v-model="projectsRegisterForm.max_amount">
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
                    type: '',
                    minmum_amount: '',
                    max_amount: '',
                    detail: ''
                }
            }
        },
        methods: {
            projectsRegister () {
                console.log(this.projectsRegisterForm)
            }
        }
    }
</script>
