<template>
    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                About micro:bit Dashboard
            </div>
            <div class="card-body">
                <div class="card-text">このダッシュボードはとある micro:bit のセンサーデータを取得し表示しています。</div>
            </div>
        </div><!-- //card mt-5-->

        <div class="card-deck mt-5">
            <div class="card">
                <div class="card-header">
                    加速度センサー
                </div>
                <div class="card-body">
                    <div class="row mt-1">
                        <div class="px-1 col-4"><div class="border border-primary rounded text-right text-truncate">{{a_x}}</div></div>
                        <div class="px-1 col-4"><div class="border border-primary rounded text-right text-truncate">{{a_y}}</div></div>
                        <div class="px-1 col-4"><div class="border border-primary rounded text-right text-truncate">{{a_z}}</div></div>
                    </div>
                    <div>
                        <canvas id="myChart" ref="mychart_a"></canvas>
                    </div>
                </div>
            </div><!-- //card-->

            <div class="card">
                <div class="card-header">
                    地磁気センサー
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="px-1 col-4"><div class="border border-primary rounded text-right text-truncate">{{mag_x}}</div></div>
                        <div class="px-1 col-4"><div class="border border-primary rounded text-right text-truncate">{{mag_y}}</div></div>
                        <div class="px-1 col-4"><div class="border border-primary rounded text-right text-truncate">{{mag_z}}</div></div>
                    </div>
                    <div>
                        <canvas id="chart_mag" ref="mychart_mag"></canvas>
                    </div>
                </div>
            </div><!-- //card mt-5-->
        </div><!-- // card-deck -->
        <div class="card-deck mt-5">
            <div class="card">
                <div class="card-header">
                    温度センサー
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mx-1 col border border-danger rounded text-right">{{temperature}}</div>
                    </div>
                </div>
                <div>
                    <canvas id="chart_temperature" ref="mychart_temperature"></canvas>
                </div>
            </div><!-- //card mt-5-->
            <div class="card">
                <div class="card-header">
                    コンパス
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mx-1 col border border-primary rounded text-right">{{bearing}}</div>
                    </div>
                </div>
                <div>
                    <canvas id="chart_bearing" ref="mychart_bearing"></canvas>
                </div>
            </div><!-- //card mt-5-->
        </div><!-- // card-deck -->



        <div class="card mt-5">
            <div class="card-header">
                A/B Button
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-danger rounded text-right">{{button_a}}</div>
                    <div class="mx-1 col border border-danger rounded text-right">{{button_b}}</div>
                </div>
            </div>
        </div><!-- //card mt-5-->

        <div class="card mt-5">
            <div class="card-header">
                IO Pin
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-primary rounded text-right">{{p0}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{p1}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{p2}}</div>
                </div>
            </div>
        </div><!-- //card mt-5-->

    </div>
</template>

<script>
    export default {
        created() {
        },
        mounted() {
            console.log('Component mounted.')
            this.$nextTick(function (){
                this.chart = new Chart(this.$refs.mychart_a, {
                    type: 'line',
                    data: { datasets: [
                            { label: 'x',
                                borderColor: 'rgb(255, 0, 0)',
                                data: [] },
                            { label: 'y',
                                borderColor: 'rgb(0, 255, 0)',
                                data: [] },
                            { label: 'z',
                                borderColor: 'rgb(0, 0, 255)',
                                data: []}
                        ] },
                    options: {
                        scales: { xAxes: [{ type: 'realtime' }] },
                        plugins: { streaming: { onRefresh: this.onRefresh, delay: 30000, duration: 1800000 } }
                    }
                });

                this.chart_mag = new Chart(this.$refs.mychart_mag, {
                    type: 'line',
                    data: { datasets: [
                            { label: 'x',
                                borderColor: 'rgb(255, 0, 0)',
                                data: [] },
                            { label: 'y',
                                borderColor: 'rgb(0, 255, 0)',
                                data: [] },
                            { label: 'z',
                                borderColor: 'rgb(0, 0, 255)',
                                data: []}
                        ] },
                    options: {
                        scales: { xAxes: [{ type: 'realtime' }] },
                        plugins: { streaming: { onRefresh: this.onRefresh, delay: 30000, duration: 1800000 } }
                    }
                });

                this.chart_temperature = new Chart(this.$refs.mychart_temperature, {
                    type: 'line',
                    data: { datasets: [
                            { label: 'temperature',
                                borderColor: 'rgb(255, 0, 0)',
                                data: [] }
                        ] },
                    options: {
                        scales: { xAxes: [{ type: 'realtime' }] },
                        plugins: { streaming: { onRefresh: this.onRefresh, delay: 30000, duration: 1800000 } }
                    }
                });

                this.chart_bearing = new Chart(this.$refs.mychart_bearing, {
                    type: 'line',
                    data: { datasets: [
                            { label: 'bearing',
                                borderColor: 'rgb(255, 0, 0)',
                                data: [] }
                        ] },
                    options: {
                        scales: { xAxes: [{ type: 'realtime' }] },
                        plugins: { streaming: { onRefresh: this.onRefresh, delay: 30000, duration: 1800000 } }
                    }
                });
            });
            // initialize data
            this.getRecent();
            this.intervalid = setInterval(function(){
                console.log('interval fired...');
                // http get API
                this.getLatest();
            }.bind(this), 10000);
        },
        data() {
            return {


                INTERVAL : 500, // interval msec for receiving event

                chart: null,
                chart_mag: null,
                chart_temperature: null,
                chart_bearing: null,

                a_x : 0,
                a_y : 0,
                a_z : 0,

                button_a: 0,
                button_b: 0,

                temperature: 0,

                bearing: 0,
                mag_x: 0,
                mag_y: 0,
                mag_z: 0,

                p0: 0,
                p1: 0,
                p2: 0,

                intervalid: 0
            }
        },
        methods: {
            addData: function(){
                let now = Date.now();
                this.ave_a_x.time = now;
                this.ave_a_x.value = Math.random();
                this.ave_a_y.time = now;
                this.ave_a_y.value = Math.random();
                this.ave_a_z.time = now;
                this.ave_a_z.value = Math.random();

                this.chart.data.datasets[0].data.push({x: this.ave_a_x.time, y: this.ave_a_x.value});
            },
            onRefresh: function (chart) {
                this.chart.data.datasets.forEach(function(dataset) {
                    // dataset.data.push({
                    //     x: Date.now(),
                    //     y: Math.random()
                    // });
                });
            },
            getLatest: function() {
                this.$http.get('/api/data/latest')
                    .then(res =>  {
                        console.log(res.data);
                        this.a_x = res.data.ave_a_x;
                        this.a_y = res.data.ave_a_y;
                        this.a_z = res.data.ave_a_z;
                        this.mag_x = res.data.ave_mag_x;
                        this.mag_y = res.data.ave_mag_y;
                        this.mag_z = res.data.ave_mag_z;
                        this.temperature = res.data.ave_temperature;
                        this.bearing = res.data.ave_bearing;

                        this.putDataToCharts(res.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            getRecent: function() {
                this.$http.get('/api/data/recent')
                    .then(res =>  {
                        console.log(res.data);
                        // order by time data
                        res.data.sort(function(a,b){
                            if( a.time < b.time ) return -1;
                            if( a.time > b.time ) return 1;
                            return 0;
                        });
                        res.data.forEach(function(elem){
                            this.putDataToCharts(elem);
                        }, this);

                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            putDataToCharts: function(data) {
                this.chart.data.datasets[0].data.push({x: new Date(data.time), y: data.ave_a_x});
                this.chart.data.datasets[1].data.push({x: new Date(data.time), y: data.ave_a_y});
                this.chart.data.datasets[2].data.push({x: new Date(data.time), y: data.ave_a_z});

                this.chart_mag.data.datasets[0].data.push({x: new Date(data.time), y: data.ave_mag_x});
                this.chart_mag.data.datasets[1].data.push({x: new Date(data.time), y: data.ave_mag_y});
                this.chart_mag.data.datasets[2].data.push({x: new Date(data.time), y: data.ave_mag_z});

                this.chart_temperature.data.datasets[0].data.push({x: new Date(data.time), y: data.ave_temperature});
                this.chart_bearing.data.datasets[0].data.push({x: new Date(data.time), y: data.ave_bearing});
            }
        }
    }
</script>
