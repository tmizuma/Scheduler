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
    .no_result {
        text-align: center;
        color: gray;
    }
    .search_target_day{
        width: 30%;
        min-width: 130px;
    }
    .room_padding {
        padding-top: 16px;
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
                <!-- カレンダー対応
                <td class="search_target_day">
                    <date-picker :date="target_date" :option="option" :limit="limit" class="data_picker_width"></date-picker>
                </td>
                -->
                <div class="search_target_day">
                    <input type="text" name="name" v-model="target_date" class="form-control" placeholder="例) 2018-04-01">
                </div>
                <td class="room_padding">会議室：
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
    import myDatepicker from 'vue-datepicker';
    export default {
        mixins: [alertComponent],
        created() {
            this.showLoading()
            this.load()
            this.getRooms()
            this.initDay()
        },
        data() {
            return {
                isLoading: false,
                schedulerList: [],
                roomList:{},
                room_id: 0,
                // カレンダー対応
//                target_date: {
//                    time: ''
//                },
                target_date: '',
                endtime: {
                    time: ''
                },

                option: {
                    type: 'day',
                    week: ['月', '火', '水', '木', '金', '土', '日'],
                    month: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                    format: 'YYYY-MM-DD',
                    placeholder: '日付',
                    inputStyle: {
                        'display': 'inline-block',
                        'padding': '6px',
                        'line-height': '22px',
                        'font-size': '16px',
                        'border': '2px solid #fff',
                        'box-shadow': '0 1px 3px 0 rgba(0, 0, 0, 0.2)',
                        'border-radius': '2px',
                        'color': '#5F5F5F'
                    },
                    color: {
                        header: '#ccc',
                        headerText: '#888'
                    },
                    buttons: {
                        ok: 'Ok',
                        cancel: 'Cancel'
                    },
                    overlayOpacity: 0.5, // 0.5 as default
                    dismissible: true // as true as default
                },
                timeoption: {
                    type: 'min',
                    week: ['月', '火', '水', '木', '金', '土', '日'],
                    month: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                    format: 'YYYY-MM-DD HH:mm'
                },
                multiOption: {
                    type: 'multi-day',
                    week: ['月', '火', '水', '木', '金', '土', '日'],
                    month: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                    format:"YYYY-MM-DD HH:mm"
                },
                limit: [{
                    type: 'weekday',
                    available: [0, 1, 2, 3, 4, 5, 6]
                },
                    {
                        type: 'fromto',
                        from: '1970-01-01',
                        to: '2099-012-31'
                    }
                ]
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
                var targetDayStr = this.getTargetDayStr();
                if (!targetDayStr.time.match(/^\d{4}\-\d{2}\-\d{2}$/)) {
                    this.showFailed('日付の値が不正です。2018-01-01の形式で入力してください。');
                    this.isButtonDisabled = false;
                    return;
                }
                axios.get('/api/scheduler/', {params:{
                    'room_id': this.room_id,
                    'target_date': targetDayStr.time
                }}).then((response) => {
                    this.schedulerList = response.data['data']
                    this.isLoading = false
                });
            },
            setTargetDay(day) {
                var day = new Date(day);
                this.target_date = this.getYyyyMmDdStr(day);
                // this.target_date.time = this.getYyyyMmDdStr(day);
            },
            initDay() {
                var day = new Date();
                if (this.$route.params.day) {
                    this.target_date = this.$route.params.day;
                    // this.target_date.time = this.$route.params.day;
                    return;
                }
                this.target_date = this.getYyyyMmDdStr(day);
                // this.target_date.time = this.getYyyyMmDdStr(day);
            },
            getRooms() {
                axios.get('/api/rooms/')
                        .then(response =>  {
                    this.roomList = response.data['data']
                    this.isLoading = false
                })
            },
            getTargetDayStr() {
                return { time: this.target_date };
                // return { time: this.target_date.time.slice(0,10)}; //カレンダー対応
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
            schedulerComponent,
            'date-picker': myDatepicker
        }
    }
</script>
