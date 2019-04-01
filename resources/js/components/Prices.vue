<template>
    <div>
        <h2>Testing Prices</h2>
        <div v-for="price in prices" v-bind:key="price.uid">
            <li>{{price.price}} & {{price.check_in_date}}</li>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Prices",
        data() {
            return {
                prices: [],
                price: {
                    uid: '',
                    name: ''
                },
                price_uid: ''
            }
        },

        created() {
            this.fetchPrices();
        },

        methods:{
            fetchPrices(){
                fetch('api/rooms/hoteluid=5c80a2d79d162&datefrom=2019-03-14&dateto=2019-08-14')
                    .then(res => res.json())
                    .then(res => {
                        this.prices = res.data
                    })
            }
        }
    }
</script>

<style scoped>

</style>
