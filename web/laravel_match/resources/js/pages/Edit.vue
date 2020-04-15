<template>
    <div>
        <h1 class="l-container__title">登録情報の編集</h1>
        <span v-if="isLogin" class="navbar__item">
                {{ username }}
            </span>
        <form class="form" @submit.prevent="update" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <label for="email">メールアドレス</label>
            <input type="text" class="form__item" id="email" v-model="editForm.email" placeholder="">
            <label for="icon-image">アイコン画像</label>
            <input class="form__item" type="file" id="icon-image" @change="onFileChange">
            <output class="form__output" v-if="preview">
                <img :src="preview" alt="選択した画像"  width="30" height="30">
            </output>
            <label for="self-introduction">自己紹介</label>
            <input type="text" class="form__item" id="self-introduction" v-model="editForm.profile_fields">
            <div class="form__button">
                <button type="submit" class="button button--inverse">update</button>
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

                // console.log(data.get('id'))

                // authストアのupdateアクションを呼び出す
                await this.$store.dispatch('auth/update', data)

                // authストアを経由せずに直接たたく userのstateが更新されないが、更新処理自体は確認
                // await axios.post('/users/' + this.editForm.id, data,{
                //     headers: {
                //         'Content-Type': 'multipart/form-data', // 画像の更新のために追加
                //         'X-HTTP-Method-Override': 'PUT', // data = new FormData これをバックエンド側に渡すためにいったんpostで送りputで上書き
                //     }
                // })

                // 更新ができたらマイページに移動する
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
                this.editForm.icon_file = event.target.files[0]
            },
            // 入力欄の値とプレビュー表示をクリアするメソッド
            reset () {
                this.preview = ''
                this.editForm.icon_file = null
                this.$el.querySelector('input[type="file"]').value = null
            },
        },
        computed: {
            isLogin () {
                return this.$store.getters['auth/check']
            },
            username () {
                return this.$store.getters['auth/username']
            }
        }
    }
</script>
