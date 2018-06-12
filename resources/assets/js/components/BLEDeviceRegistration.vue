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
                </form>
            </div>
        </div><!-- //card mt-5-->

        <div class="card mt-5">
            <div class="card-header">
                Device Id
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-primary rounded text-right">{{device_id}}</div>
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
        },
        data() {
            return {
                // Serial Number Service
                // 0000180A00001000800000805F9B34FB
                // 00002A2500001000800000805F9B34FB


                DEVICE_INFORMATION_SERVICE_UUID         : '0000180a-0000-1000-8000-00805f9b34fb',
                DEVICE_INFORMATION_SERIAL_NUMBER_CHARACTERISTIC_UUID : '00002a25-0000-1000-8000-00805f9b34fb',
                device_id: '',

                INTERVAL : 500, // interval msec for receiving event

                device : null,
                characteristic : null,
            }
        },
        methods: {
            connect: function(){
                navigator.bluetooth.requestDevice({
                    filters: [{
                        namePrefix: 'BBC micro:bit',
                    }],
                    optionalServices: [this.DEVICE_INFORMATION_SERVICE_UUID]
                })
                    .then(device => {
                        this.device = device;
                        return device.gatt.connect();
                    })
                    .then(server =>{
                        console.log("server", server);
                        return Promise.all([
                            server.getPrimaryService(this.DEVICE_INFORMATION_SERVICE_UUID)
                        ]);
                    })
                    .then(service => {
                        console.log("service", service);
                        return Promise.all([
                            service[0].getCharacteristic(this.DEVICE_INFORMATION_SERIAL_NUMBER_CHARACTERISTIC_UUID)
                    ]);
                    })
                    .then(chara => {
                        console.log("ACCELEROMETER:", chara);
                        alert("BLE Connection Established");
                        this.characteristic = chara[0];

                        this.characteristic.readValue()
                            .then(value => {
                                let message = value.buffer;
                                console.log(new Uint8Array(message));
                                this.device_id = new TextDecoder("utf-8").decode(message);
                            });
                    })
                    .catch(error => {
                        alert("Faild to establish BLE connection. Please try again.");
                        console.log(error);
                    });
            },
            disconnect: function() {
                if (!this.device || !this.device.gatt.connected) return ;
                this.device.gatt.disconnect();
                alert("BLE connection disconnected ;)");
            },
            postData: function() {
                let payload = {
                    time: this.ave_a_x.time,
                    ave_a_x: this.ave_a_x.value,
                    ave_a_y: this.ave_a_y.value,
                    ave_a_z: this.ave_a_z.value,
                    ave_mag_x: this.ave_mag_x.value,
                    ave_mag_y: this.ave_mag_y.value,
                    ave_mag_z: this.ave_mag_z.value,
                    ave_temperature: this.ave_temperature.value,
                    ave_bearing: this.ave_bearing.value
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
