<template>
    <div>
        <h1 class="l-container__title">登録情報の編集</h1>

        <form class="form" @submit.prevent="update" enctype="multipart/form-data" method="POST">
            <div v-if="updateErrors" class="errors">
                <ul v-if="updateErrors.email">
                    <li v-for="msg in updateErrors.email" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.icon_file">
                    <li v-for="msg in updateErrors.icon_file" :key="msg">{{ msg }}</li>
                </ul>
                <ul v-if="updateErrors.profile_fields">
                    <li v-for="msg in updateErrors.profile_fields" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <input type="hidden" name="_method" value="PUT">
            <label for="email">メールアドレス</label>
            <input type="text" class="form__item" id="email" v-model="editForm.email" :placeholder="email">
            <label for="icon-image">アイコン画像</label>
            <img :src="icon_path" alt="アイコン画像"  height="20">
            <input class="form__item" type="file" id="icon-image" @change="onFileChange">
            <output class="form__output" v-if="preview">
                <img :src="preview" alt="選択した画像"  width="30" height="30">
            </output>
            <label for="self-introduction">自己紹介</label>
            <input type="text" class="form__item" id="self-introduction" v-model="editForm.profile_fields" :placeholder="profile_fields">
            <div class="form__button">
                <button type="submit" class="button button--inverse">更新する</button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                editForm: {
                    id: this.$store.getters['auth/userid'],
                    email: '',
                    icon_file: '',
                    profile_fields: ''
                },
                preview: null,
            }
        },
        methods: {
            async update () {
                const data = new FormData()
                data.append('id',this.editForm.id)
                data.append('email',this.editForm.email)
                data.append('file',this.editForm.icon_file)
                data.append('profile_fields',this.editForm.profile_fields)

                // authストアのupdateアクションを呼び出す
                await this.$store.dispatch('auth/update', data)

                if (this.apiStatus) {
                    // updateアクションが成功だった場合、マイページに移動する
                    this.$router.push('/mypage')
                }
            },
            clearError () {
                this.$store.commit('auth/setUpdateErrorMessages', null)
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
                this.editForm.icon_file = event.target.files[0]
            },
            // 入力欄の値とプレビュー表示をクリアするメソッド
            reset () {
                this.preview = ''
                this.editForm.icon_file = null
                this.$el.querySelector('input[type="file"]').value = null
            }
        },
        created() {
            this.clearError ()
        },
        computed: {
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },
            updateErrors () {
                return this.$store.state.auth.updateErrorMessages
            },
            isLogin () {
                return this.$store.getters['auth/check']
            },
            email () {
                return this.$store.getters['auth/email']
            },
            // icon_path () {
            //     return this.$store.getters['auth/icon_path']
            // },
            icon_path () {
                // 画面をリロードすると、相対パスの場合表示できない
                // メンテナンスしづらいので、可能であれば修正する
                return 'http://0.0.0.0:3000/' + this.$store.getters['auth/icon_path']
            },
            profile_fields () {
                return this.$store.getters['auth/profile_fields']
            }
        }
    }
</script>
