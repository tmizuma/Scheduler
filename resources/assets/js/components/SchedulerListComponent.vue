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
    .day_button {
        width: 30px;
        border-radius: 5px;
    }
    .today_button {
        width: 50px;
        border-radius: 5px;
    }
    .no_result {
        text-align: center;
        color: gray;
    }
    .search_target_day{
        width: 42%;
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
                <td class="search_target_day">
                    日付：<input type="text" name="name" style="padding: 2px;" v-model="target_date" placeholder="2018-01-01">
                    <button class="day_button" @click="addDay">+</button>　
                    <button class="day_button" @click="minusDay">-</button>　
                    <button class="today_button" @click="today">今日</button>　
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
        <div class="no_result" v-if="this.schedulerList.length==0">対象の会議室予約はありません。</div>
        <div v-show="isLoading">
            <loading-component></loading-component>
        </div>
    </div>
</div>
</template>

<script>
    import LoadingComponent from './LoadingComponent.vue'
    import schedulerComponent from './SchedulerComponent.vue'
    import alertComponent from './mixin/Alert.vue';
    export default {
        mixins: [alertComponent],
        created() {
            this.today()
            this.showLoading()
            this.load()
            this.getRooms()
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
                if (this.$route.params.day) {
                    this.setTargetDay(this.$route.params.day);
                    axios.get('/api/scheduler/', {params:{
                            'target_date': this.$route.params.day
                        }}).then((response) => {
                            this.schedulerList = response.data['data']
                        this.isLoading = false
                    });
                } else {
                    axios.get('/api/scheduler/')
                            .then(res => {
                        this.schedulerList = res.data['data']
                    this.isLoading = false
                })
                }
            },
            search() {
                if (!this.getTargetDayStr().match(/^\d{4}\-\d{2}\-\d{2}$/)) {
                    this.showFailed('日付の値が不正です。2018-01-01の形式で入力してください。');
                    this.isButtonDisabled = false;
                    return;
                }
                axios.get('/api/scheduler/', {params:{
                    'room_id': this.room_id,
                    'target_date': this.getTargetDayStr()
                }}).then((response) => {
                    this.schedulerList = response.data['data']
                    this.isLoading = false
                });
            },
            setTargetDay(day) {
                var day = new Date(day);
                this.target_date = this.getYyyyMmDdStr(day) + '(' + this.getWeekStr(day) + ')';
            },
            today() {
                var day = new Date();
                this.target_date = this.getYyyyMmDdStr(day) + '(' + this.getWeekStr(day) + ')';
            },
            addDay() {
                var day = new Date(this.getTargetDayStr());
                day.setDate(day.getDate() + 1);
                var yyyymmdd = this.getYyyyMmDdStr(day);
                this.target_date = yyyymmdd + '(' + this.getWeekStr(day) + ')';
            },
            minusDay() {
                var day = new Date(this.getTargetDayStr());
                day.setDate(day.getDate() - 1);
                var yyyymmdd = this.getYyyyMmDdStr(day);
                this.target_date = yyyymmdd + '(' + this.getWeekStr(day) + ')';
            },
            getRooms() {
                axios.get('/api/rooms/')
                        .then(response =>  {
                    this.roomList = response.data['data']
                    this.isLoading = false
                })
            },
            getTargetDayStr() {
                return this.target_date.slice(0,10);
            },
            getWeekStr(day) {
                return [ "日", "月", "火", "水", "木", "金", "土" ][day.getDay()]
            },
            getYyyyMmDdStr(day) {
                return day.getFullYear() + '-' +
                        ( "0" + ( day.getMonth() + 1 ) ).slice(-2) + '-' +
                        ( "0" + day.getDate() ).slice(-2);
            }
        },
        components: {
            LoadingComponent,
            schedulerComponent
        }
    }
</script>
