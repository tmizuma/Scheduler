
<template>
<div>
    <h3>キーワード登録</h3>
    カンマ区切りでキーワードを入力してください。
    <textarea class="form-control" v-model="keywords" rows="3"></textarea>
    <br>
    <label>
        <span v-bind:disabled="isButtonDisabled" class="btn btn-success">
            CSVでアップロード
            <input type="file" v-bind:disabled="isButtonDisabled"  @change="selectedFile" style="display:none">
        </span>
    </label>
    <br>
    <br>
    <button type="button" v-on:click="submit" v-bind:disabled="isButtonDisabled" class="btn btn-primary">登録</button>
    <router-link :to="'/'" v-bind:disabled="isButtonDisabled" tag="button" class="btn btn-secondary">戻る</router-link>
</div>
</template>

<script>
    import alertComponent from './mixin/Alert.vue';
    export default {
        mixins: [alertComponent],
        data() {
            return {
                isButtonDisabled: true,
                keywords: ''
            }
        },
        created() {
            this.load()
        },
        methods: {
            load() {
                axios.get('/api/keyword/' + this.$route.params.id)
                        .then(res =>  {
                    var data = res.data['data']
                    if (data == null || data.length == 0) {
                        this.isButtonDisabled = false;
                        return;
                    }
                    data.forEach(keyword => {
                        this.keywords += keyword.keyword + ',';
                    });
                    this.keywords = this.keywords.slice( 0, -1 ) ;
                    this.isButtonDisabled = false;
                })
            },
            selectedFile: function(e) {
                var self = this;
                this.isButtonDisabled = true;
                e.preventDefault();
                let file = e.target.files[0];
                if (!this.checkCsvExtension(file)) {
                    self.isButtonDisabled = false;
                    this.showFailed('アップロードできるのはCSVファイルのみです。');
                    return;
                }
                var reader = new FileReader();
                reader.readAsText(file);
                reader.onload = function(ev){
                    var text = reader.result;
                    text = text.replace(/\r?\n/g,"")
                        .replace("'","")
                        .replace(/[\"]/g,"")
                        .replace(" ","")
                        .replace("　","");
                    self.keywords = text;
                    self.isButtonDisabled = false;
                }
            },
            checkCsvExtension(file) {
                var ret = false;
                var fileTypes = file.name.split(".");
                var len = fileTypes.length;
                if (len === 0) {
                    return ret;
                }
                ret = fileTypes[len - 1];
                return ret == 'csv';
            },
            submit() {
                this.isButtonDisabled = true;
                if (this.keywords == 0) {
                    this.showFailed('キーワードが入力されていません。');
                    this.isButtonDisabled = false;
                    return;
                }
                var array = this.keywords.split(',');
                var count = array.length;
                if (!window.confirm("元のキーワードは全て上書かれます。\n" + count + '件のキーワードを登録しまか？')) {
                    this.isButtonDisabled = false;
                    return;
                }
                axios.put('/api/keyword/' + this.$route.params.id, {'keywords': this.keywords}).then(
                    (response) => {
                        this.showSuccess('登録に成功しました。');
                        location.href = '/';
                    }).catch( error => {
                        this.showFailed('登録に失敗しました。');
                        this.isButtonDisabled = false;
                    });
            },
        }
    }
</script>

