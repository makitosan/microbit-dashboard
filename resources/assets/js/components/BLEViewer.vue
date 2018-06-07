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

                a_x : 0,
                a_y : 0,
                a_z : 0,

                characteristic : null,
                accelerometer_device: null
            }
        },
        methods: {
            connect: function(){
                navigator.bluetooth.requestDevice({
                    filters: [{
                        namePrefix: 'BBC micro:bit',
                    }],
                    optionalServices: [this.ACCELEROMETERSERVICE_SERVICE_UUID]
                })
                    .then(device => {
                        accelerometer_device = device;
                        console.log("device", device);
                        return device.gatt.connect();
                    })
                    //ACCELEROMETER
                    .then(server =>{
                        console.log("server", server)
                        return server.getPrimaryService(this.ACCELEROMETERSERVICE_SERVICE_UUID);
                    })
                    .then(service => {
                        console.log("service", service)
                        return service.getCharacteristic(this.ACCELEROMETERDATA_CHARACTERISTIC_UUID)
                    })
                    .then(chara => {
                        console.log("ACCELEROMETER:", chara)
                        alert("BLE Connection Established");
                        this.characteristic = chara;
                        this.characteristic.startNotifications();
                        this.characteristic.addEventListener('characteristicvaluechanged',this.onAccelerometerValueChanged);
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
            }
        }
    }
</script>
