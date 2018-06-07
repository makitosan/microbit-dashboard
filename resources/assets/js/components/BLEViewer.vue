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
                Accelerator
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mx-1 col border border-primary rounded text-right">{{a_x}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{a_y}}</div>
                    <div class="mx-1 col border border-primary rounded text-right">{{a_z}}</div>
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


    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                ACCELEROMETERSERVICE_SERVICE_UUID       : 'e95d0753-251d-470a-a062-fa1922dfa9a8',
                ACCELEROMETERDATA_CHARACTERISTIC_UUID   : 'e95dca4b-251d-470a-a062-fa1922dfa9a8',
                BUTTON_SERVICE_UUID : 'e95d9882-251d-470a-a062-fa1922dfa9a8',
                BUTTON_A_CHARACTERISTIC_UUID : 'e95dda90-251d-470a-a062-fa1922dfa9a8',
                BUTTON_B_CHARACTERISTIC_UUID : 'e95dda91-251d-470a-a062-fa1922dfa9a8',
                TEMPERATURE_SERVICE_UUID : 'e95d6100-251d-470a-a062-fa1922dfa9a8',
                TEMPERATURE_CHARACTERISTIC_UUID : 'e95d9250-251d-470a-a062-fa1922dfa9a8',

                a_x : 0,
                a_y : 0,
                a_z : 0,

                button_a: 0,
                button_b: 0,

                temperature: 0,

                characteristic : null,
                chara_button_a : null,
                chara_button_b : null,
                chara_temp : null,

                accelerometer_device: null
            }
        },
        methods: {
            connect: function(){
                navigator.bluetooth.requestDevice({
                    filters: [{
                        namePrefix: 'BBC micro:bit',
                    }],
                    optionalServices: [this.ACCELEROMETERSERVICE_SERVICE_UUID,
                        this.BUTTON_SERVICE_UUID,
                        this.TEMPERATURE_SERVICE_UUID]
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
                            server.getPrimaryService(this.TEMPERATURE_SERVICE_UUID)
                        ]);
                    })
                    .then(service => {
                        console.log("service", service);
                        return Promise.all([
                            service[0].getCharacteristic(this.ACCELEROMETERDATA_CHARACTERISTIC_UUID),
                            service[1].getCharacteristic(this.BUTTON_A_CHARACTERISTIC_UUID),
                            service[1].getCharacteristic(this.BUTTON_B_CHARACTERISTIC_UUID),
                            service[2].getCharacteristic(this.TEMPERATURE_CHARACTERISTIC_UUID)
                        ]);
                    })
                    .then(chara => {
                        console.log("ACCELEROMETER:", chara);
                        alert("BLE Connection Established");
                        this.characteristic = chara[0];
                        this.characteristic.startNotifications();
                        this.characteristic.addEventListener('characteristicvaluechanged',this.onAccelerometerValueChanged);

                        this.chara_button_a = chara[1];
                        this.chara_button_a.startNotifications();
                        this.chara_button_a.addEventListener('characteristicvaluechanged', this.onchangeABtn);
                        this.chara_button_b = chara[2];
                        this.chara_button_b.startNotifications();
                        this.chara_button_b.addEventListener('characteristicvaluechanged', this.onchangeBBtn);

                        this.chara_temp = chara[3];
                        this.chara_temp.startNotifications();
                        this.chara_temp.addEventListener('characteristicvaluechanged',this.onTemperaturChanged);

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
            },
            onAccelerometerValueChanged: function(event) {
                this.a_x = event.target.value.getUint16(0)/1000.0;
                this.a_y = event.target.value.getUint16(2)/1000.0;
                this.a_z = event.target.value.getUint16(4)/1000.0;
            },
            onTemperaturChanged: function(event) {
                this.temperature = event.target.value;
            },
            onchangeABtn: function(event) {
                this.button_a++;
            },
            onchangeBBtn: function(event) {
                this.button_b++;
            }
        }
    }
</script>
