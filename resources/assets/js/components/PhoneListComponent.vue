<style scoped>
    .table_panel {
        color: #ffffff;
        background-color: #4582ec;
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
        border-color: #4582ec;
    }
</style>
<template>
<div class="">
    <div class="table_panel">電話番号一覧</div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>窓口</th><th>電話番号</th><th>担当者</th><th>詳細</th><th class="text-right">
                <router-link :to="'/phone/new/'" tag="button" class="btn btn-primary">新規登録</router-link>
            </th>
            </tr>
            </thead>
            <tbody>
                <phone-component v-for="phone in phonelist" v-bind:key="phone.id" :phone="phone"></phone-component>
            </tbody>
        </table>
        <div v-show="isLoading">
            <loading-component></loading-component>
        </div>
    </div>
</div>
</template>

<script>
    import LoadingComponent from './LoadingComponent.vue'
    import PhoneComponent from './PhoneComponent.vue'
    export default {
        created() {
            this.showLoading()
            this.load()
        },
        data() {
            return {
                isLoading: false,
                phonelist: []
            }
        },
        methods: {
            showLoading() {
                this.isLoading = true
            },
            load() {
                axios.get('/api/phone/')
                        .then(res =>  {
                    this.phonelist = res.data['data']
                    this.isLoading = false
                })
            },
        },
        components: {
            LoadingComponent,
            PhoneComponent
        }
    }
</script>
