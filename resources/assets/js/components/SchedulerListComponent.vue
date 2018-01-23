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
        <table class="table">
            <thead>
            <tr>
                <th></th><th></th><th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="width: 30%">
                    日付：<input type="text" name="name" style="padding: 2px;" v-model="target_date" placeholder="2018-01-01">
                </td>
                <td>会議室：
                    <select v-model="room_id" style="height: 28px;">
                        <option v-for="room in roomList" v-bind:value="room.id">{{ room.name }}</option>
                    </select>
                </td>
                <td style="text-align: right;">
                    <button type="button" @click="search" class="btn btn-secondary">検索</button>
                </td>
            </tr>
            </tbody>
        </table>
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
            this.getRooms()
            this.today()
        },
        data() {
            return {
                isLoading: false,
                schedulerList: [],
                roomList:{},
                target_date: '',
                room_id: 0
            }
        },
        methods: {
            showLoading() {
                this.isLoading = true
            },
            load() {
                axios.get('/api/scheduler/')
                        .then(res => {
                    this.schedulerList = res.data['data']
                    this.isLoading = false
                })
            },
            search() {
                axios.get('/api/scheduler/', {params:{
                    'room_id': this.room_id,
                    'target_date': this.target_date,
                }}).then((response) => {
                    this.schedulerList = response.data['data']
                    this.isLoading = false
                });
            },
            today() {
                var now = new Date();
                var yyyymmdd = now.getFullYear() + '-' +
                        ( "0"+( now.getMonth()+1 ) ).slice(-2)+ '-' +
                        ( "0"+now.getDate() ).slice(-2);
                this.target_date = yyyymmdd;
            },
            getRooms() {
                axios.get('/api/rooms/')
                        .then(response =>  {
                    this.roomList = response.data['data']
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
