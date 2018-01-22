
<template>
<tr class="phone_item" v-if="isShow">
    <td>{{ phone.department }}</td>
    <td>{{ phone.phone_number }}</td>
    <td>{{ phone.person_in_charge }}</td>
    <td>{{ phone.description }}</td>
    <td class="text-right">
        <router-link :to="'/keyword/' + this.phone.id" tag="button" class="btn btn-success">キーワード</router-link>
        <router-link :to="'/phone/edit/' + this.phone.id" tag="button" class="btn btn-info">編集</router-link>
        <button type="button" class="btn btn-warning" v-on:click="deleteItem">削除</button>
    </td>
    </tr>
</template>

<script>
    import alertComponent from './mixin/Alert.vue';
    export default {
        props: ['phone'],
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
                var id = this.phone.id;
                axios.delete('/api/phone/' + id).then(
                    (response) => {
                        this.showSuccess('削除が完了しました。');
                    }).catch( error => {
                        this.showFailed('削除に失敗しました。');
                    });
            }
        }
    }
</script>

