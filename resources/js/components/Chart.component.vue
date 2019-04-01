<template>
    <div class="example">
        <apexcharts width="750" height="525" type="line" :options="chartOptions" :series="series"></apexcharts>
    </div>
</template>

<script>
    import VueApexCharts from 'vue-apexcharts'

    export default {
        name: 'Chart',
        components: {
            apexcharts: VueApexCharts,
        },
        data: function () {
            return {
                chartOptions: {
                    chart: {
                        id: 'basic-bar'
                    },
                    xaxis: {
                        categories: ['March', 'April', 'May', 'June', 'July', 'August', 'Sept', 'Oct', 'Nov', 'December', 'January', 'February']
                    }
                },
                series: [
                    {
                        name: 'Hotel Emona',
                        data: [125.5, 127.5, 130.42, 128.9, 132.4, 127.6, 126.5, 130.8, 135, 133.2, 131.1, 132.6],
                    },
                    {
                        name: 'Hotel Latinum',
                        data: [127.5, 130.5, 127.42, 133.9, 128.4, 124.6, 128.5, 139.8, 137, 141.2, 136.133, 134.6],
                    }
                ],
            }
        },
        created() {
            this.fetchPrices();
        },
        methods: {
            updateChart() {
                const max = 90;
                const min = 20;
                const newData = this.series[0].data.map(() => {
                    return Math.floor(Math.random() * (max - min + 1)) + min
                })

                const colors = ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0']

                // Make sure to update the whole options config and not just a single property to allow the Vue watch catch the change.
                this.chartOptions = {
                    colors: [colors[Math.floor(Math.random() * colors.length)]]
                };
                // In the same way, update the series option
                this.series = [{
                    data: newData
                }]
            },

            fetchPrices() {
                fetch('api/rooms/hoteluid=5c80a2d79d162&datefrom=2019-03-14&dateto=2019-08-14')
                    .then(res => res.json())
                    .then(res => {

                        const priceArray = [];
                        // const checkInArray =[];
                        res.data.forEach(function (item) {
                            priceArray.push(item.price)
                            // this.series = [{
                            //     data: item.price
                            // }]
                        });

                        // res.data.forEach(function (item) {
                        //     checkInArray.push(item.check_in_date)
                        //     // this.series = [{
                        //     //     data: item.price
                        //     // }]
                        // });

                        // console.log(this.chartOptions.xaxis.categories)


                        // this.series = [{
                        //     data: priceArray
                        // }];
                    })
            }
        }
    }
</script>
