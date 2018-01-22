
<template>
<tr class="scheduler_item" v-if="isShow">
    <td>{{ scheduler.room_name }}</td>
    <td>{{ scheduler.start_time }} ~ {{ scheduler.end_time }}</td>
    <td>{{ scheduler.user_name }}</td>
    <td>{{ scheduler.description }}</td>
    <td class="text-right">
        <router-link :to="'/scheduler/edit/' + this.scheduler.id" tag="button" class="btn btn-info">編集</router-link>
        <button type="button" class="btn btn-warning" v-on:click="deleteItem">削除</button>
    </td>
    </tr>
</template>

<script>
    import alertComponent from './mixin/Alert.vue';
    export default {
        props: ['scheduler'],
        mixins: [alertComponent],
        data() {
            return {
                isShow: true
            }
        },
        methods: {
            deleteItem() {
                if (!window.confirm('削除してもよろしいですか？')) {
                    return;
                }
                this.isShow = false;
                var id = this.scheduler.id;
                axios.delete('/api/scheduler/' + id).then(
                    (response) => {
                        this.showSuccess('削除が完了しました。');
                    }).catch( error => {
                        this.showFailed('削除に失敗しました。');
                    });
            }
        }
    }
</script>

