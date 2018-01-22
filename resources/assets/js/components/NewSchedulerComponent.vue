<style>
    .required {
        color: red;
    }
</style>
<template>
<div class="">
    <div class="container">
        <h3>電話番号新規登録</h3>
            <div class="form-group">
                <label class="control-label col-xs-2">窓口<span class="required"> *</span></label>
                <div class="col-xs-5">
                    <input type="text" v-model="department" name="name" class="form-control" placeholder="例) 経理部">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-2">電話番号<span class="required"> *</span></label>
                <div class="col-xs-5">
                    <input type="text" name="name" v-model="phone_number" class="form-control" placeholder="例) 050-1234-5678">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-2">担当者</label>
                <div class="col-xs-5">
                    <input type="text" name="name" v-model="person_in_charge" class="form-control" placeholder="例) 山田 太郎">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-2">説明</label>
                <div class="col-xs-5">
                    <input type="text" name="name" v-model="description" class="form-control" placeholder="例) 経費の申請を行います">
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    <button v-bind:disabled="isButtonDisabled" @click="postData" class="btn btn-primary">登録</button>
                    <router-link :to="'/'" v-bind:disabled="isButtonDisabled" tag="button" class="btn btn-secondary">戻る</router-link>
                </div>
            </div>
    </div>
</div>
</template>
<script>
    import alertComponent from './mixin/Alert.vue';
    export default {
        mixins: [alertComponent],
        data() {
            return {
                phone_number: '',
                department: '',
                person_in_charge: '',
                description: '',
                isButtonDisabled: false
            }
        },
        methods: {
            postData() {
                this.isButtonDisabled = true;
                if (this.phone_number.length == 0 || this.department.length == 0) {
                    this.showFailed('必須項目が入力されていません。');
                    this.isButtonDisabled = false;
                    return;
                }
                if (!this.phone_number.match(/^\d{2,5}-\d{1,4}-\d{4}$/)) {
                    this.showFailed('電話番号はハイフン付き数字で入力してください。');
                    this.isButtonDisabled = false;
                    return;
                }

                axios.post('/api/phone/', {
                    'phone_number': this.phone_number,
                    'department': this.department,
                    'person_in_charge': this.person_in_charge,
                    'description': this.description,
                }).then(
                    (response) => {
                        this.showSuccess('登録に成功しました。');
                        location.href = '/';
                    }).catch( error => {
                        this.showFailed('入力内容に誤りがあります。');
                        this.isButtonDisabled = false;
                    });
            }
        }
    }
</script>
