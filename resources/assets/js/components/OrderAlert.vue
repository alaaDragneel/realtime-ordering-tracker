<template>
    <div>
       <alert v-model="showAlert" placement="top-right" duration="4000" type="info" width="400px" dismissable>
          <span class="icon-ok-circled alert-icon-float-left"></span>
          <strong>Order Status Updated!</strong>
          <p>Order ID: {{ order_id }} Has Been Updated.</p>
        </alert>
    </div>
</template>

<script>
    import { alert } from 'vue-strap'
    export default {
        props: ['user_id'],
        components: {
            alert
        },
        data () {
            return {
                showAlert: false,
                order_id: null
            };
        },
        mounted() {
            Echo.channel('pizza-tracker')
                .listen('OrderStatusChanged', (order) => {
                   if (this.user_id == order.user_id) {
                       this.showAlert = true;
                       this.order_id = order.id;
                   }
            });
        }
    }
</script>

