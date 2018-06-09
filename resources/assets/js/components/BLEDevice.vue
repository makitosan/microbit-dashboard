<template>
    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                CONNECT / DISCONNECT
            </div>
            <div class="card-body">
                <form>
                    <div class="form-row">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-block" v-on:click="connect">CONNECT</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-light btn-block" v-on:click="disconnect">DISCONNECT</button>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-block" v-on:click="addData">ADD DATA</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- //card mt-5-->

        <div class="card mt-5">
            <div class="card-header">
                Accelerator
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-primary rounded text-right">{{a_x}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{a_y}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{a_z}}</div>
                </div>
                <div class="row mt-1">
                    <div class="mx-1 col border border-primary rounded text-right">{{ave_a_x.value}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{ave_a_y.value}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{ave_a_z.value}}</div>
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
                Temperatue
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-danger rounded text-right">{{temperature}}</div>
                </div>
            </div>
        </div><!-- //card mt-5-->

        <div class="card mt-5">
            <div class="card-header">
                Magnetometer / Compass
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
            this.intervalid_heartbeat = setInterval(function(){
                console.log('heartbeat fired...');
                this.$http.post('/api/heartbeat')
                    .then(res =>  {
                        console.log(res.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }.bind(this), 60000);
        },
        data() {
            return {
                ACCELEROMETERSERVICE_SERVICE_UUID       : 'e95d0753-251d-470a-a062-fa1922dfa9a8',
                ACCELEROMETERDATA_CHARACTERISTIC_UUID   : 'e95dca4b-251d-470a-a062-fa1922dfa9a8',
                ACCELEROMETERPERIOD_CHARACTERISTIC_UUID : 'e95dfb24-251d-470a-a062-fa1922dfa9a8',

                BUTTON_SERVICE_UUID : 'e95d9882-251d-470a-a062-fa1922dfa9a8',
                BUTTON_A_CHARACTERISTIC_UUID : 'e95dda90-251d-470a-a062-fa1922dfa9a8',
                BUTTON_B_CHARACTERISTIC_UUID : 'e95dda91-251d-470a-a062-fa1922dfa9a8',

                TEMPERATURE_SERVICE_UUID : 'e95d6100-251d-470a-a062-fa1922dfa9a8',
                TEMPERATURE_CHARACTERISTIC_UUID : 'e95d9250-251d-470a-a062-fa1922dfa9a8',
                TEMPERATUREPERIOD_CHARACTERISTIC_UUID : 'e95d1b25-251d-470a-a062-fa1922dfa9a8',

                MAGNETOMETER_SERVICE_UUID : 'e95df2d8-251d-470a-a062-fa1922dfa9a8',
                MAGNETOMETER_DATA_UUID    : 'e95dfb11-251d-470a-a062-fa1922dfa9a8',
                MAGNETOMETER_PERIOD_UUID  : 'e95d386c-251d-470a-a062-fa1922dfa9a8',
                MAGNETOMETER_COMPASS_UUID : 'e95d9715-251d-470a-a062-fa1922dfa9a8',

                IO_PIN_SERVICE_UUID : 'e95d127b-251d-470a-a062-fa1922dfa9a8',
                IO_PIN_DATA_CHARACTERISTIC_UUID : 'e95d8d00-251d-470a-a062-fa1922dfa9a8',

                INTERVAL : 500, // interval msec for receiving event

                a_x : 0,
                a_y : 0,
                a_z : 0,

                ave_a_x : {time: Date.now(), value: 0},
                ave_a_y : {time: Date.now(), value: 0},
                ave_a_z : {time: Date.now(), value: 0},

                total_a_x : [],
                total_a_y : [],
                total_a_z : [],

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

                characteristic : null,
                chara_button_a : null,
                chara_button_b : null,
                chara_temp : null,

                chara_magnetometer : null,
                chara_compass : null,

                io_pin : null,

                accelerometer_device: null,

                intervalid: 0,
                intervalid_heartbeat: 0
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

                // invoke save API
                console.log('data API fired...');
                this.postData();
            },
            connect: function(){
                navigator.bluetooth.requestDevice({
                    filters: [{
                        namePrefix: 'BBC micro:bit',
                    }],
                    optionalServices: [this.ACCELEROMETERSERVICE_SERVICE_UUID,
                        this.BUTTON_SERVICE_UUID,
                        this.TEMPERATURE_SERVICE_UUID,
                        this.MAGNETOMETER_SERVICE_UUID,
                        this.IO_PIN_SERVICE_UUID]
                })
                    .then(device => {
                        this.accelerometer_device = device;
                        console.log("device", device);
                        return device.gatt.connect();
                    })
                    //ACCELEROMETER
                    .then(server =>{
                        console.log("server", server);
                        return Promise.all([
                            server.getPrimaryService(this.ACCELEROMETERSERVICE_SERVICE_UUID),
                            server.getPrimaryService(this.BUTTON_SERVICE_UUID),
                            server.getPrimaryService(this.TEMPERATURE_SERVICE_UUID),
                            server.getPrimaryService(this.MAGNETOMETER_SERVICE_UUID),
                            server.getPrimaryService(this.IO_PIN_SERVICE_UUID)
                        ]);
                    })
                    .then(service => {
                        console.log("service", service);
                        return Promise.all([
                            service[0].getCharacteristic(this.ACCELEROMETERDATA_CHARACTERISTIC_UUID),
                            service[0].getCharacteristic(this.ACCELEROMETERPERIOD_CHARACTERISTIC_UUID),
                            service[1].getCharacteristic(this.BUTTON_A_CHARACTERISTIC_UUID),
                            service[1].getCharacteristic(this.BUTTON_B_CHARACTERISTIC_UUID),
                            service[2].getCharacteristic(this.TEMPERATURE_CHARACTERISTIC_UUID),
                            service[2].getCharacteristic(this.TEMPERATUREPERIOD_CHARACTERISTIC_UUID),
                            service[3].getCharacteristic(this.MAGNETOMETER_DATA_UUID),
                            service[3].getCharacteristic(this.MAGNETOMETER_COMPASS_UUID),
                            service[3].getCharacteristic(this.MAGNETOMETER_PERIOD_UUID),
                            service[4].getCharacteristic(this.IO_PIN_DATA_CHARACTERISTIC_UUID)
                    ]);
                    })
                    .then(chara => {
                        console.log("ACCELEROMETER:", chara);
                        alert("BLE Connection Established");
                        this.characteristic = chara[0];
                        this.characteristic.startNotifications();
                        this.characteristic.addEventListener('characteristicvaluechanged',this.onAccelerometerValueChanged);

                        chara[1].writeValue(new Uint16Array([this.INTERVAL * 2])); // period

                        this.chara_button_a = chara[2];
                        this.chara_button_a.startNotifications();
                        this.chara_button_a.addEventListener('characteristicvaluechanged', this.onchangeABtn);
                        this.chara_button_b = chara[3];
                        this.chara_button_b.startNotifications();
                        this.chara_button_b.addEventListener('characteristicvaluechanged', this.onchangeBBtn);

                        this.chara_temp = chara[4];
                        this.chara_temp.startNotifications();
                        this.chara_temp.addEventListener('characteristicvaluechanged',this.onTemperaturChanged);

                        chara[5].writeValue(new Uint16Array([this.INTERVAL])); // period

                        this.chara_magnetometer = chara[6];
                        this.chara_magnetometer.startNotifications();
                        this.chara_magnetometer.addEventListener('characteristicvaluechanged',this.onMagnetometerChanged);

                        this.chara_compass = chara[7];
                        this.chara_compass.startNotifications();
                        this.chara_compass.addEventListener('characteristicvaluechanged',this.onCompassChanged);

                        chara[8].writeValue(new Uint16Array([this.INTERVAL])); // period

                        this.io_pin = chara[9];
                        this.io_pin.startNotifications();
                        this.io_pin.addEventListener('characteristicvaluechanged',this.onIOPinChanged);

                        this.intervalid = setInterval(function(){
                            console.log('interval fired...');
                            console.log(this.total_a_x);
                            console.log(this.total_a_y);
                            console.log(this.total_a_z);

                            // calc average
                            let tmp_a_x = [], tmp_a_y = [], tmp_a_z = [];
                            this.total_a_x.forEach(function(elm) {
                                tmp_a_x.push(elm);
                            });
                            this.total_a_y.forEach(function(elm) {
                                tmp_a_y.push(elm);
                            });
                            this.total_a_z.forEach(function(elm) {
                                tmp_a_z.push(elm);
                            });

                            let now = Date.now();
                            this.ave_a_x.time = now;
                            this.ave_a_x.value = this.average(tmp_a_x);
                            this.ave_a_y.time = now;
                            this.ave_a_y.value = this.average(tmp_a_y);
                            this.ave_a_z.time = now;
                            this.ave_a_z.value = this.average(tmp_a_z);

                            // reduce to recent 20 elements
                            if (this.total_a_x.length > 20) {
                                this.total_a_x.splice(0, this.total_a_x.length - 20);
                            }
                            if (this.total_a_y.length > 20) {
                                this.total_a_y.splice(0, this.total_a_y.length - 20);
                            }
                            if (this.total_a_z.length > 20) {
                                this.total_a_z.splice(0, this.total_a_z.length - 20);
                            }

                            // invoke save API
                            console.log('data API fired...');
                            this.postData();

                        }.bind(this), 10000);
                    })
                    .catch(error => {
                        alert("Faild to establish BLE connection. Please try again.");
                        console.log(error);
                    });
            },
            disconnect: function() {
                if (!this.accelerometer_device || !this.accelerometer_device.gatt.connected) return ;
                this.accelerometer_device.gatt.disconnect();
                alert("BLE connection disconnected ;)");
                clearInterval(this.intervalid);
            },
            onAccelerometerValueChanged: function(event) {
                this.a_x = event.target.value.getUint16(0)/1000.0;
                this.a_y = event.target.value.getUint16(2)/1000.0;
                this.a_z = event.target.value.getUint16(4)/1000.0;

                this.total_a_x.push(this.a_x);
                this.total_a_y.push(this.a_y);
                this.total_a_z.push(this.a_z);

                let now = Date.now();
                this.chart.data.datasets[0].data.push({x: now, y: this.a_x});
                this.chart.data.datasets[1].data.push({x: now, y: this.a_y});
                this.chart.data.datasets[2].data.push({x: now, y: this.a_z});
            },
            onTemperaturChanged: function(event) {
                this.temperature = event.target.value.getUint8(0, true);
            },
            onchangeABtn: function(event) {
                let swd = event.target.value.getUint8(0, true);
                if (swd === 1) this.button_a++;
            },
            onchangeBBtn: function(event) {
                let swd = event.target.value.getUint8(0, true);
                if (swd === 1) this.button_b++;
            },
            onMagnetometerChanged: function(event) {
                this.mag_x = event.target.value.getUint16(0)/1000.0;
                this.mag_y = event.target.value.getUint16(2)/1000.0;
                this.mag_z = event.target.value.getUint16(4)/1000.0;
            },
            onCompassChanged: function(event) {
                this.bearing = event.target.value.getUint16(0, true);
            },
            onIOPinChanged: function(event) {
                console.log(event);
                this.p0 = event.target.value[0].getUint8(0, true);
                this.p1 = event.target.value[1].getUint8(0, true);
                this.p2 = event.target.value[2].getUint8(0, true);
            },
            average : function(arr, fn) {
                return this.sum(arr, fn) / arr.length;
            },
            sum : function(arr, fn) {
                if (fn) {
                    return this.sum(arr.map(fn));
                }
                else {
                    return arr.reduce(function(prev, current, i, arr) {
                        return prev+current;
                    });
                }
            },
            postData: function() {
                let payload = {
                    time: this.ave_a_x.time,
                    ave_a_x: this.ave_a_x.value,
                    ave_a_y: this.ave_a_y.value,
                    ave_a_z: this.ave_a_z.value
                };
                this.$http.post('/api/data', payload)
                    .then(res =>  {
                        console.log(res.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>
