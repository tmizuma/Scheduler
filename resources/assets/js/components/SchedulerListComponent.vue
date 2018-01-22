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
    <div class="table_panel">会議室予約一覧</div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>会議室名</th><th>時間</th><th>予約者</th><th>内容</th><th class="text-right">
                <router-link :to="'/scheduler/new/'" tag="button" class="btn btn-primary">新規登録</router-link>
            </th>
            </tr>
            </thead>
            <tbody>
                <scheduler-component v-for="scheduler in schedulerList" v-bind:key="scheduler.id" :scheduler="scheduler"></scheduler-component>
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
    import schedulerComponent from './SchedulerComponent.vue'
    export default {
        created() {
            this.showLoading()
            this.load()
        },
        data() {
            return {
                isLoading: false,
                schedulerList: []
            }
        },
        methods: {
            showLoading() {
                this.isLoading = true
            },
            load() {
                axios.get('/api/scheduler/')
                        .then(res =>  {
                    this.schedulerList = res.data['data']
                    this.isLoading = false
                })
            },
        },
        components: {
            LoadingComponent,
            schedulerComponent
        }
    }
</script>
