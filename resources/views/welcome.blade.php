<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <title>mirobit: web bluetooth API example</title>
</head>

<body>
<div id="app">
    <example-component></example-component>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Special Thanks
            </div>
            <div class="card-body">
                <h5 class="card-title">Inspired by @yokmama</h5>
                <p class="card-text">To connect BLE devise via Web Bluetooth API is a little bit time consuming.
                    The following @yokmama's article helped me a lot. Thank you very much.</p>
                <p>
                    <a href="https://qiita.com/yokmama/items/5522fabfb5b9623278e2" class="" target="_blank">
                        https://qiita.com/yokmama/items/5522fabfb5b9623278e2
                    </a>
                </p>
            </div>
        </div><!-- //card -->
        <div class="card mt-5">
            <div class="card-header">
                Accelerator
            </div>
            <div class="card-body">
                <form name="js">
                    <input type="button" value="CONNECT" onclick="connect();"/>
                    <input type="button" value="DISCONNECT" onclick="disconnect();" />
                    X:<input type="text" name="x" value="" />
                    Y:<input type="text" name="y" value="" />
                    Z:<input type="text" name="z" value="" />
                </form>
            </div>
        </div><!-- //card mt-5-->
    </div>
</div><!-- // #app end -->

<script>
    var accelerometer_device;
    var accelerometer_characteristic;

    //micro:bit BLE UUID
    var ACCELEROMETERSERVICE_SERVICE_UUID       = 'e95d0753-251d-470a-a062-fa1922dfa9a8';
    var ACCELEROMETERDATA_CHARACTERISTIC_UUID   = 'e95dca4b-251d-470a-a062-fa1922dfa9a8';

    var BUTTON_SERVICE_UUID = 'e95d9882-251d-470a-a062-fa1922dfa9a8';
    var BUTTON_A_CHARACTERISTIC_UUID = 'e95dda90-251d-470a-a062-fa1922dfa9a8';
    var BUTTON_B_CHARACTERISTIC_UUID = 'e95dda91-251d-470a-a062-fa1922dfa9a8';



    function connect() {
        navigator.bluetooth.requestDevice({
            filters: [{
                namePrefix: 'BBC micro:bit',
            }],
            optionalServices: [ACCELEROMETERSERVICE_SERVICE_UUID]
        })
            .then(device => {
                accelerometer_device = device;
                console.log("device", device);
                return device.gatt.connect();
            })
            //ACCELEROMETER
            .then(server =>{
                console.log("server", server)
                return server.getPrimaryService(ACCELEROMETERSERVICE_SERVICE_UUID);
            })
            .then(service => {
                console.log("service", service)
                return service.getCharacteristic(ACCELEROMETERDATA_CHARACTERISTIC_UUID)
            })
            .then(chara => {
                console.log("ACCELEROMETER:", chara)
                alert("BLE接続が完了しました。");
                characteristic = chara;
                characteristic.startNotifications();
                characteristic.addEventListener('characteristicvaluechanged',onAccelerometerValueChanged);
            })
            .catch(error => {
                alert("Faild to establish BLE connection. Please try again.");
                console.log(error);
            });
    }

    function onAccelerometerValueChanged(event) {
        AcceleratorX = event.target.value.getUint16(0)/1000.0;
        console.log('x:' + AcceleratorX);
        document.js.x.value = AcceleratorX;

        AcceleratorY = event.target.value.getUint16(2)/1000.0;
        console.log('y:' + AcceleratorY);
        document.js.y.value = AcceleratorY;

        AcceleratorZ = event.target.value.getUint16(4)/1000.0;
        console.log('z:' + AcceleratorZ);
        document.js.z.value = AcceleratorZ;

    }

    function disconnect() {
        if (!accelerometer_device || !accelerometer_device.gatt.connected) return ;
        accelerometer_device.gatt.disconnect();
        alert("BLE connection established ;)")
    }

</script>
<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>

