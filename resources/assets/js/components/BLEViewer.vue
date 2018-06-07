<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        <button v-on:click="connectDevice">CONNECT</button>
                    </div>
                </div>
            </div>
        </div>
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
                a_z : 0
            }
        },
        methods: {
            connectDevice: function(){
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
                        characteristic = chara;
                        characteristic.startNotifications();
                        characteristic.addEventListener('characteristicvaluechanged',this.onAccelerometerValueChanged);
                    })
                    .catch(error => {
                        alert("Faild to establish BLE connection. Please try again.");
                        console.log(error);
                    });
            },
            onAccelerometerValueChanged: function(event) {
                this.a_x = event.target.value.getUint16(0)/1000.0;
                this.a_y = event.target.value.getUint16(2)/1000.0;
                this.a_z = event.target.value.getUint16(4)/1000.0;
            }
        }
    }
</script>
