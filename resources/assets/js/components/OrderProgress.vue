<template>
    <div>
        <div class="progress">
            <progressbar :now="progress" :type="progressbarColor" striped animated></progressbar>
        </div>
        <div class="order-status">
            <strong>Order Status</strong> {{ newStatus }}
        </div>

        <img src="/img/delivery.gif" alt="delivery" v-if="progress >= 100">
    </div>
</template>

<script>
    import { progressbar } from 'vue-strap';
    export default {
        props: ['status', 'initial', 'order_id'],
        components: {
            progressbar
        },
        computed: {
            progressbarColor() {
                if (this.progress >= 0 && this.progress <= 10) {
                    return 'danger';
                } else if (this.progress > 10 && this.progress <= 30) {
                    return 'warning';
                } else if (this.progress > 30 && this.progress <= 50) {
                    return 'info';
                } else if (this.progress > 50 && this.progress <= 70) {
                    return 'primary';
                } else if (this.progress > 70 && this.progress <= 100) {
                    return 'success';
                }

                return 'success';
            }
        },
        data () {
            return {
                newStatus: this.status,
                progress: this.initial,
            };
        },
        mounted() {
            Echo.channel(`pizza-tracker.${this.order_id}`)
                .listen('OrderStatusChanged', (order) => {
                   console.log('Realtime Tracker Working', order);
                   this.newStatus = order.status_name;
                   this.progress = order.status_percent;
            });
        }
    }
</script>

