<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <title>mirobit: web bluetooth API example</title>

    <script>
        var ave_a_x = {time: Date.now(), value: Math.random()};
    </script>

</head>

<body>
<div id="app">
    <ble-viewer :ave_a_x="ave_a_x"></ble-viewer>
</div><!-- // #app end -->
<div class="container">
    <canvas id="myChart"></canvas>

</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-streaming@1.4.1/dist/chartjs-plugin-streaming.js"></script>
<script src="{{ mix('/js/app.js') }}"></script>
<script>
</script>
</body>
</html>
