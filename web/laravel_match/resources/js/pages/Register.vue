<template>
    <div>
        <h1 class="l-container__title">ユーザー登録</h1>
        <form class="form" @submit.prevent="register" enctype="multipart/form-data">
            <label for="username">お名前</label>
            <input type="text" class="form__item" id="username" v-model="registerForm.name">
            <label for="email">メールアドレス</label>
            <input type="text" class="form__item" id="email" v-model="registerForm.email">
            <label for="password">パスワード</label>
            <input type="password" class="form__item" id="password" v-model="registerForm.password">
            <label for="password-confirmation">パスワード (再入力)</label>
            <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
            <label for="icon-image">アイコン画像</label>
            <input class="form__item" type="file" id="icon-image" @change="onFileChange">
            <output class="form__output" v-if="preview">
                <img :src="preview" alt=""  width="30" height="30">
            </output>
            <label for="self-introduction">自己紹介</label>
            <input type="text" class="form__item" id="self-introduction" v-model="registerForm.profile_fields">
            <div class="form__button">
                <button type="submit" class="button button--inverse">register</button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
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
                // const formData = new FormData()
                // formData.append('name',this.registerForm.name)
                // formData.append('email',this.registerForm.email)
                // formData.append('password',this.registerForm.password)
                // formData.append('password_confirmation',this.registerForm.password_confirmation)
                // formData.append('file',this.registerForm.icon_file)
                // formData.append('profile_fields',this.registerForm.profile_fields)
                // console.log(formData)

                console.log(this.registerForm)

                // authストアのregisterアクションを呼び出す
                await this.$store.dispatch('auth/register', this.registerForm)

                // 登録ができたらマイページに移動する
                this.$router.push('/mypage')
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
        }
    }
</script>
