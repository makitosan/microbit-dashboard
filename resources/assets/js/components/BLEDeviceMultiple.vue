<template>
    <div class="container">
        <div class="alert alert-info" role="alert">
            <strong>READ ME</strong> This example trys to connect multiple devices.
        </div>
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
                <ul>
                    <li v-for="(device, index) in devices">{{index}} - {{device.id}} {{device.name}}</li>
                </ul>
            </div>
        </div><!-- //card mt-5-->
        <div class="card mt-5">
            <div class="card-header">
                TEMPERATURE
            </div>
            <div class="card-body">
                <ul>
                    <li v-for="value, key in temperatures">{{key}} {{value}}</li>
                </ul>
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


                DEVICE_INFORMATION_SERVICE_UUID         : '0000180a-0000-1000-8000-00805f9b34fb',
                TEMPERATURE_SERVICE_UUID : 'e95d6100-251d-470a-a062-fa1922dfa9a8',
                TEMPERATURE_CHARACTERISTIC_UUID : 'e95d9250-251d-470a-a062-fa1922dfa9a8',
                TEMPERATUREPERIOD_CHARACTERISTIC_UUID : 'e95d1b25-251d-470a-a062-fa1922dfa9a8',

                INTERVAL : 500, // interval msec for receiving event

                device : null,
                characteristic : null,

                charEvents: [],

                temperatures: {},

                devices: [],
            }
        },
        methods: {
            connect: function(){
                navigator.bluetooth.requestDevice({
                    filters: [{
                        namePrefix: 'BBC micro:bit',
                    }],
                    optionalServices: [this.DEVICE_INFORMATION_SERVICE_UUID,
                                        this.TEMPERATURE_SERVICE_UUID]
                })
                    .then(device => {
                        this.devices.push(device);
                        return device.gatt.connect();
                    })
                    .then(server =>{
                        console.log("server", server);
                        return Promise.all([
                            server.getPrimaryService(this.DEVICE_INFORMATION_SERVICE_UUID),
                            server.getPrimaryService(this.TEMPERATURE_SERVICE_UUID)
                        ]);
                    })
                    .then(service => {
                        console.log("service", service);
                        return Promise.all([
                            service[1].getCharacteristic(this.TEMPERATURE_CHARACTERISTIC_UUID),
                            service[1].getCharacteristic(this.TEMPERATUREPERIOD_CHARACTERISTIC_UUID)
                        ]);
                    })
                    .then(chara => {
                        console.log("chara:", chara);
                        alert("BLE Connection Established");

                        this.chara_temp = chara[0];
                        this.chara_temp.startNotifications();
                        this.chara_temp.addEventListener('characteristicvaluechanged', this.onChanged);
                        chara[1].writeValue(new Uint16Array([this.INTERVAL])); // period
                    })
                    .catch(error => {
                        alert("Faild to establish BLE connection. Please try again.");
                        console.log(error);
                    });
            },
            disconnect: function() {
                this.devices.forEach(function (device) {
                    if (!device || !device.gatt.connected) return;
                    device.gatt.disconnect();
                });
                this.devices = [];
                alert("BLE connection disconnected ;)");
            },
            onChanged: function(event) {
                // this.temperatures.set(event.target.service.device.id, event.target.value.getUint8(0, true));
                console.log(this.temperatures);
                this.temperatures[event.target.service.device.id] = event.target.value.getUint8(0, true);
                this.$forceUpdate();
            }
        }
    }
</script>
