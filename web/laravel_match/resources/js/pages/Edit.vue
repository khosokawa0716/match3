<template>
    <section class="l-container">
        <h1 class="l-container__title">本人情報の編集</h1>

        <form class="c-form" @submit.prevent="update" enctype="multipart/form-data" method="POST">
            <div v-if="updateErrors" class="c-error">
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

            <label class="c-input__label">メールアドレス</label>
            <input type="text" class="c-input c-input__l" v-model="editForm.email" placeholder="メールアドレス">
            <label class="c-input__label">今のアイコン画像</label>
            <img :src="editForm.icon_path" alt="今のアイコン画像" height="100" class="imgIcon_l">
            <label class="c-input__label">新しいアイコン画像</label>
            <label class="u-fontSizeSmall">&#8251;1MB以下の画像を選択してください。</label>
            <input class="form__item" type="file" id="icon-image" @change="onFileChange">
            <output class="form__output" v-if="preview">
                <img :src="preview" alt="選択した画像" height="100" class="imgIcon_l">
            </output>
            <label class="c-input__label">自己紹介</label>
            <textarea type="text" class="c-input c-input__textarea" v-model="editForm.profile_fields" placeholder="自己紹介（120文字以内）"></textarea>
            <button type="submit" class="c-btn c-btn__corp c-btn__l">更新する</button>
        </form>
    </section>
</template>
<script>
    import {OK} from "../util";

    export default {
        title: '本人情報の編集',
        data () {
            return {
                editForm: {
                    id: this.$store.getters['auth/userid'],
                    email: '',
                    icon_path: '',
                    icon_file: '',
                    profile_fields: ''
                },
                preview: null,
            }
        },
        methods: {
            // 編集しようとするユーザーの情報をとってくる
            async fetchUser () {
                // UserController@editを起動
                // 返却されたオブジェクトをresponseに代入
                const response = await axios.get(`/api/users/${this.editForm.id}/edit`)

                // エラーの場合
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 返却されたユーザー情報を初期値としてinputに代入
                this.user = response.data
                this.editForm.email = this.user.email
                this.editForm.icon_path = this.user.icon_path
                this.editForm.profile_fields = this.user.profile_fields
            },

            // ユーザー情報の更新
            async update () {
                // 画像データを扱うためnew FormData()を定義、入力項目を代入する
                const data = new FormData()
                data.append('id',this.editForm.id)
                data.append('email',this.editForm.email)
                data.append('file',this.editForm.icon_file)
                data.append('profile_fields',this.editForm.profile_fields)

                // authストアのupdateアクションを呼び出す
                await this.$store.dispatch('auth/update', data)

                if (this.apiStatus) {
                    // updateアクションが成功だった場合、ストアにメッセージを格納する
                    this.$store.commit('message/setContent', {
                        content: 'お客様の情報を更新しました！',
                        timeout: 5000
                    })

                    // マイページに移動する
                    this.$router.push('/mypage')
                }
            },

            // アイコン画像のプレビューを表示する
            onFileChange: function(event) {
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

            // 入力欄の値とプレビュー表示をクリアする
            reset: function() {
                this.preview = ''
                this.editForm.icon_file = null
                this.$el.querySelector('input[type="file"]').value = null
            },

            // ストアerror.jsにあるコードをクリアする
            clearError: function() {
                this.$store.commit('auth/setUpdateErrorMessages', null)
            },
        },
        created() {
            // 一度エラーが出た後、ブラウザバックなどで戻ってきたときにクリアする
            this.clearError ()
        },
        computed: {
            // ストアでの処理が成功したかどうか
            apiStatus () {
                return this.$store.state.auth.apiStatus
            },

            // 入力時にエラーがあった場合、メッセージを格納する
            updateErrors () {
                return this.$store.state.auth.updateErrorMessages
            },

            // ログインしているかどうか
            isLogin () {
                return this.$store.getters['auth/check']
            },
        },

        // 初期値を反映させるために、画面遷移直後にfetchUserメソッドを呼ぶ
        watch: {
            $route: {
                async handler () {
                    await this.fetchUser()
                },
                immediate: true
            }
        }
    }
</script>
