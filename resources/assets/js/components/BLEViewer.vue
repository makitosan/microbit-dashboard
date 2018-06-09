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

        <div class="card mt-5">
            <div class="card-header">
                加速度センサー
            </div>
            <div class="card-body">
                <div class="row mt-1">
                    <div class="mx-1 col border border-primary rounded text-right">{{ave_a_x.value}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{ave_a_y.value}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{ave_a_z.value}}</div>
                </div>
                <div>
                    <canvas id="myChart" ref="mychart"></canvas>
                </div>
            </div>
        </div><!-- //card mt-5-->

        <div class="card mt-5">
            <div class="card-header">
                温度センサー
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-danger rounded text-right">{{temperature}}</div>
                </div>
            </div>
        </div><!-- //card mt-5-->

        <div class="card mt-5">
            <div class="card-header">
                地磁気センサー / コンパス
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-primary rounded text-right">{{mag_x}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{mag_y}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{mag_z}}</div>
                </div>
                <div class="row mt-1">
                    <div class="mx-1 col border border-primary rounded text-right">{{bearing}}</div>
                </div>
            </div>
        </div><!-- //card mt-5-->

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
                this.chart = new Chart(this.$refs.mychart, {
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
                        plugins: { streaming: { onRefresh: this.onRefresh, delay: 30000, duration: 180000 } }
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

                ave_a_x : {time: Date.now(), value: 0},
                ave_a_y : {time: Date.now(), value: 0},
                ave_a_z : {time: Date.now(), value: 0},

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
                        this.ave_a_x.time = res.data.time;
                        this.ave_a_x.value = res.data.ave_a_x;
                        this.ave_a_y.time = res.data.time;
                        this.ave_a_y.value = res.data.ave_a_y;
                        this.ave_a_z.time = res.data.time;
                        this.ave_a_z.value = res.data.ave_a_z;

                        this.chart.data.datasets[0].data.push({x: new Date(res.data.time), y: res.data.ave_a_x});
                        this.chart.data.datasets[1].data.push({x: new Date(res.data.time), y: res.data.ave_a_y});
                        this.chart.data.datasets[2].data.push({x: new Date(res.data.time), y: res.data.ave_a_z});

                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            getRecent: function() {
                this.$http.get('/api/data/recent')
                    .then(res =>  {
                        console.log(res.data);
                        res.data.forEach(function(elem){
                            this.chart.data.datasets[0].data.push({x: new Date(elem.time), y: elem.ave_a_x});
                            this.chart.data.datasets[1].data.push({x: new Date(elem.time), y: elem.ave_a_y});
                            this.chart.data.datasets[2].data.push({x: new Date(elem.time), y: elem.ave_a_z});
                        }, this);

                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>
