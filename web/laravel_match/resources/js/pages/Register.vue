<template>
    <section class="l-container">
        <h1 class="l-container__title">ユーザー登録</h1>
        <form class="c-form" @submit.prevent="register" enctype="multipart/form-data">
            <div v-if="registerErrors" class="c-error">
                <ul v-if="registerErrors.name">
                    <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.email">
                    <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.password">
                    <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.password_confirmation">
                    <li v-for="msg in registerErrors.password_confirmation" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.icon_file">
                    <li v-for="msg in registerErrors.icon_file" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.profile_fields">
                    <li v-for="msg in registerErrors.profile_fields" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <input type="text" class="c-input c-input__l" v-model="registerForm.name" placeholder="お名前（3〜10文字）">
            <input type="text" class="c-input c-input__l" v-model="registerForm.email" placeholder="メールアドレス">
            <input type="password" class="c-input c-input__l" v-model="registerForm.password" placeholder="パスワード（半角英数8〜16文字）">
            <input type="password" class="c-input c-input__l" v-model="registerForm.password_confirmation" placeholder="パスワード (再入力)">
            <label for="icon-image">アイコン画像</label>
            <label class="u-fontSizeSmall">&#8251;1MB以下の画像を選択してください。</label>
            <output v-if="preview">
                <img :src="preview" alt="選択した画像" height="100" class="imgIcon_l">
            </output>
            <input class="c-input c-input__l" type="file" id="icon-image" @change="onFileChange">
            <textarea cols="30" class="c-input c-input__textarea" rows="10" v-model="registerForm.profile_fields" placeholder="自己紹介（120文字以内）"></textarea>
            <button type="submit" class="c-btn c-btn__corp c-btn__l">登録する</button>
        </form>
    </section>
</template>
<script>
    export default {
        title: 'ユーザー登録 - ',
        data () {
            return {
                registerForm: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    icon_file: '',
                    profile_fields: ''
                },
                preview: null,
            }
        },
        methods: {
            async register () {
                const data = new FormData()
                data.append('name',this.registerForm.name)
                data.append('email',this.registerForm.email)
                data.append('password',this.registerForm.password)
                data.append('password_confirmation',this.registerForm.password_confirmation)
                data.append('file',this.registerForm.icon_file)
                data.append('profile_fields',this.registerForm.profile_fields)
                console.log(data)

                console.log(this.registerForm)

                // authストアのregisterアクションを呼び出す
                await this.$store.dispatch('auth/register', data)

                if (this.apiStatus) {
                    // registerアクションが成功だった場合、マイページに移動する
                    this.$router.push('/mypage')
                }
            },
            clearError () {
                this.$store.commit('auth/setRegisterErrorMessages', null)
            },
            onFileChange (event) {
                // アイコン画像のプレビューを表示するメソッド
                // 何も選択されていなかったら処理中断
                if (event.target.files.length === 0) {
                    this.reset()
                    return false
                }

                // ファイルが画像ではなかったら処理中断
                if (! event.target.files[0].type.match('image.*')) {
                    this.reset()
                    return false
                }

                // ファイルが画像が1メガバイト(1,048,576バイト)より大きかったら処理中断
                if (event.target.files[0].size > 1048576) {
                    this.reset()
                    return false
                }

                // FileReaderクラスのインスタンスを取得
                const reader = new FileReader()

                // ファイルを読み込み終わったタイミングで実行する処理
                reader.onload = e => {
                    // previewに読み込み結果（データURL）を代入する
                    // previewに値が入ると<output>につけたv-ifがtrueと判定される
                    // また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
                    // 結果として画像が表示される
                    this.preview = e.target.result
                }

                // ファイルを読み込む
                // 読み込まれたファイルはデータURL形式で受け取れる（上記onload参照）
                reader.readAsDataURL(event.target.files[0])
                this.registerForm.icon_file = event.target.files[0]
            },
            // 入力欄の値とプレビュー表示をクリアするメソッド
            reset () {
                this.preview = ''
                this.registerForm.icon_file = null
                this.$el.querySelector('input[type="file"]').value = null
            },
        },
        created() {
            this.clearError()
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },
            registerErrors () {
                return this.$store.state.auth.registerErrorMessages
            }
        }
    }
</script>
