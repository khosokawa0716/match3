<template>
    <div>
        <h1 class="l-container__title">案件の編集</h1>
        <form class="form" @submit.prevent="projectsUpdate">
            <div v-if="updateErrors" class="errors">
                <ul v-if="updateErrors.title">
                    <li v-for="msg in updateErrors.title" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.type">
                    <li v-for="msg in updateErrors.type" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.minimum_amount">
                    <li v-for="msg in updateErrors.minimum_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.max_amount">
                    <li v-for="msg in updateErrors.max_amount" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.detail">
                    <li v-for="msg in updateErrors.detail" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <input type="hidden" name="_method" value="PUT">
            <label for="title">タイトル</label>
            <input type="text" class="form__item" id="title" v-model="projectsUpdateForm.title">
            <input type="radio" id="one-off" value="one-off" v-model="projectsUpdateForm.type">
            <label for="one-off">決まった金額を支払う</label>
            <input type="radio" id="service" value="service" v-model="projectsUpdateForm.type">
            <label for="service">サービス開始後の売り上げを分け合う</label>
            <div v-if="isOneOff">
                <label for="minimum-amount">下限金額</label>
                <input type="number" class="form__item" id="minimum-amount" max="10000000" v-model="projectsUpdateForm.minimum_amount">
                <label for="max-amount">上限金額</label>
                <input type="number" class="form__item" id="max-amount" max="10000000" v-model="projectsUpdateForm.max_amount">
            </div>
            <label for="detail">詳細</label>
            <input type="text" class="form__item" id="detail" v-model="projectsUpdateForm.detail">
            <div class="form__button">
                <button type="submit" class="button button--inverse">案件を更新する</button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                projectsUpdateForm: {
                    // id: this.$store.getters['project/projectid'],

                    // ストアでない場合はこっちかな もしかするとこっちならURLが取得できるのかな
                    id: this.$route.params.projectId,
                    title: '',
                    type: '',
                    minimum_amount: '',
                    max_amount: '',
                    detail: ''
                }
            }
        },
        methods: {
            async projectsUpdate () {
                console.log(this.projectsUpdateForm)
                // projectストアのupdateアクションを呼び出す
                await this.$store.dispatch('project/update', this.projectsUpdateForm)
                // いったんprojectのストア管理をやめてみる
                // await axios.put('/projects/' + this.projectsUpdateForm.id, this.projectsUpdateForm)

                if (this.apiStatus) {
                    // updateアクションが成功だった場合、ストアにメッセージを格納する
                    this.$store.commit('message/setContent', {
                        content: '案件を更新しました！',
                        timeout: 5000
                    })

                    // そのあとマイページに移動する
                    this.$router.push('/mypage')
                }
            },
            clearError () {
                this.$store.commit('project/setUpdateErrorMessages', null)
            },
        },
        // created() {
        //     //初期値の設定
        //     console.log('created()の起動')
        //     this.projectsUpdateForm.title = this.$store.state.project.title
        // },
        computed: {
            apiStatus () {
                return this.$store.state.project.apiStatus
            },
            updateErrors () {
                return this.$store.state.project.updateErrorMessages
            },
            isOneOff () {
                return this.projectsUpdateForm.type === 'one-off';
            },
            title () {
                return this.$store.state.project.title
            },
            type () {
                return this.$store.state.project.type
            },
            minimum_amount () {
                return this.$store.state.project.minimum_amount
            },
            max_amount () {
                return this.$store.state.project.max_amount
            },
            detail () {
                return this.$store.state.project.detail
            },
        }
    }
</script>
