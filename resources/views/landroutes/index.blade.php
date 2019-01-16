{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--<meta name="viewport" content="initial-scale=1.0, user-scalable=no">--}}
{{--<meta charset="utf-8">--}}
{{--<title>Transit Layer</title>--}}
{{--<style>--}}
{{--/* Always set the map height explicitly to define the size of the div--}}
{{--* element that contains the map. */--}}
{{--#map {--}}
{{--height: 100%;--}}
{{--}--}}
{{--/* Optional: Makes the sample page fill the window. */--}}
{{--html, body {--}}
{{--height: 100%;--}}
{{--margin: 0;--}}
{{--padding: 0;--}}
{{--}--}}
{{--#floating-panel {--}}
{{--position: absolute;--}}
{{--top: 10px;--}}
{{--left: 25%;--}}
{{--z-index: 5;--}}
{{--background-color: #fff;--}}
{{--padding: 5px;--}}
{{--border: 1px solid #999;--}}
{{--text-align: center;--}}
{{--font-family: 'Roboto','sans-serif';--}}
{{--line-height: 30px;--}}
{{--padding-left: 10px;--}}
{{--}--}}
{{--</style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div id="floating-panel">--}}
{{--<b>Mode of Travel: </b>--}}
{{--<select id="mode">--}}
{{--<option value="DRIVING">Driving</option>--}}
{{--<option value="WALKING">Walking</option>--}}
{{--<option value="BICYCLING">Bicycling</option>--}}
{{--<option value="TRANSIT">Transit</option>--}}
{{--</select>--}}
{{--</div>--}}
{{--<div id="map" style="width: 50%;"></div>--}}
{{--<!-- Replace the value of the key parameter with your own API key. -->--}}
{{--<script async defer--}}
{{--src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg&callback=initMap">--}}
{{--</script>--}}
{{--<script>--}}
{{--function initMap() {--}}
{{--var directionsDisplay = new google.maps.DirectionsRenderer;--}}
{{--var directionsService = new google.maps.DirectionsService;--}}
{{--var map = new google.maps.Map(document.getElementById('map'), {--}}
{{--zoom: 14,--}}
{{--center: {lat: 37.77, lng: -122.447}--}}
{{--});--}}
{{--directionsDisplay.setMap(map);--}}

{{--calculateAndDisplayRoute(directionsService, directionsDisplay);--}}
{{--document.getElementById('mode').addEventListener('change', function() {--}}
{{--calculateAndDisplayRoute(directionsService, directionsDisplay);--}}
{{--});--}}
{{--}--}}

{{--function calculateAndDisplayRoute(directionsService, directionsDisplay) {--}}
{{--var selectedMode = document.getElementById('mode').value;--}}
{{--directionsService.route({--}}
{{--origin: {lat: 52.50453, lng: 13.497233},  // Haight.--}}
{{--destination: {lat: 52.500679, lng: 13.336966},  // Ocean Beach.--}}
{{--// Note that Javascript allows us to access the constant--}}
{{--// using square brackets and a string value as its--}}
{{--// "property."--}}
{{--travelMode: google.maps.TravelMode[selectedMode]--}}
{{--}, function(response, status) {--}}
{{--if (status == 'OK') {--}}
{{--directionsDisplay.setDirections(response);--}}
{{--} else {--}}
{{--window.alert('Directions request failed due to ' + status);--}}
{{--}--}}
{{--});--}}
{{--}--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}

{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--<meta name="viewport" content="initial-scale=1.0, user-scalable=no">--}}
{{--<meta charset="utf-8">--}}
{{--<title>Displaying Text Directions With setPanel()</title>--}}
{{--<style>--}}
{{--/* Always set the map height explicitly to define the size of the div--}}
{{--* element that contains the map. */--}}
{{--#map {--}}
{{--height: 100%;--}}
{{--}--}}

{{--/* Optional: Makes the sample page fill the window. */--}}
{{--html, body {--}}
{{--height: 100%;--}}
{{--margin: 0;--}}
{{--padding: 0;--}}
{{--}--}}

{{--#floating-panel {--}}
{{--position: absolute;--}}
{{--top: 10px;--}}
{{--left: 25%;--}}
{{--z-index: 5;--}}
{{--background-color: #fff;--}}
{{--padding: 5px;--}}
{{--border: 1px solid #999;--}}
{{--text-align: center;--}}
{{--font-family: 'Roboto', 'sans-serif';--}}
{{--line-height: 30px;--}}
{{--padding-left: 10px;--}}
{{--}--}}

{{--#right-panel {--}}
{{--font-family: 'Roboto', 'sans-serif';--}}
{{--line-height: 30px;--}}
{{--padding-left: 10px;--}}
{{--}--}}

{{--#right-panel select, #right-panel input {--}}
{{--font-size: 15px;--}}
{{--}--}}

{{--#right-panel select {--}}
{{--width: 100%;--}}
{{--}--}}

{{--#right-panel i {--}}
{{--font-size: 12px;--}}
{{--}--}}

{{--#right-panel {--}}
{{--height: 100%;--}}
{{--float: right;--}}
{{--width: 390px;--}}
{{--overflow: auto;--}}
{{--}--}}

{{--#map {--}}
{{--margin-right: 400px;--}}
{{--}--}}

{{--#floating-panel {--}}
{{--background: #fff;--}}
{{--padding: 5px;--}}
{{--font-size: 14px;--}}
{{--font-family: Arial;--}}
{{--border: 1px solid #ccc;--}}
{{--box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);--}}
{{--display: none;--}}
{{--}--}}

{{--@media print {--}}
{{--#map {--}}
{{--height: 500px;--}}
{{--margin: 0;--}}
{{--}--}}

{{--#right-panel {--}}
{{--float: none;--}}
{{--width: auto;--}}
{{--}--}}
{{--}--}}
{{--</style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div id="floating-panel">--}}
{{--<strong>Start:</strong>--}}
{{--<select id="start">--}}
{{--<option value="Berlin, Germany">Berlin</option>--}}
{{--<option value="Munich, Germany">Munich</option>--}}
{{--<option value="Hamburg, Germany">Hamburg</option>--}}
{{--</select>--}}
{{--<br>--}}
{{--<strong>End:</strong>--}}
{{--<select id="end">--}}
{{--<option value="Berlin, Germany">Berlin</option>--}}
{{--<option value="Munich, Germany">Munich</option>--}}
{{--<option value="Hamburg, Germany">Hamburg</option>--}}
{{--</select>--}}
{{--</div>--}}
{{--<div id="right-panel"></div>--}}
{{--<div id="map"></div>--}}
{{--<script>--}}
{{--function initMap() {--}}
{{--var directionsDisplay = new google.maps.DirectionsRenderer;--}}
{{--var directionsService = new google.maps.DirectionsService;--}}
{{--var map = new google.maps.Map(document.getElementById('map'), {--}}
{{--zoom: 7,--}}
{{--center: {lat: 52.5196617, lng: 13.4004545}--}}
{{--});--}}
{{--directionsDisplay.setMap(map);--}}
{{--directionsDisplay.setPanel(document.getElementById('right-panel'));--}}

{{--var control = document.getElementById('floating-panel');--}}
{{--control.style.display = 'block';--}}
{{--map.controls[google.maps.ControlPosition.TOP_CENTER].push(control);--}}

{{--var onChangeHandler = function () {--}}
{{--calculateAndDisplayRoute(directionsService, directionsDisplay);--}}
{{--};--}}
{{--document.getElementById('start').addEventListener('change', onChangeHandler);--}}
{{--document.getElementById('end').addEventListener('change', onChangeHandler);--}}
{{--}--}}

{{--function calculateAndDisplayRoute(directionsService, directionsDisplay) {--}}
{{--var start = document.getElementById('start').value;--}}
{{--var end = document.getElementById('end').value;--}}
{{--directionsService.route({--}}
{{--origin: start,--}}
{{--destination: end,--}}
{{--travelMode: 'TRANSIT'--}}
{{--}, function (response, status) {--}}
{{--if (status === 'OK') {--}}
{{--directionsDisplay.setDirections(response);--}}
{{--} else {--}}
{{--window.alert('Directions request failed due to ' + status);--}}
{{--}--}}
{{--});--}}
{{--}--}}
{{--</script>--}}
{{--<script async defer--}}
{{--src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg&callback=initMap">--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}

@extends ('layouts/header')


@section('title','Land Routes')

@section('header-style')
    <style>
        /* Always set the map height explicitly to define the size of the div
        * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto', 'sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }

        #right-panel {
            font-family: 'Roboto', 'sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }

        #right-panel select, #right-panel input {
            font-size: 15px;
        }

        #right-panel select {
            width: 100%;
        }

        #right-panel i {
            font-size: 12px;
        }

        #right-panel {
            height: 100%;
            float: right;
            width: 390px;
            overflow: auto;
        }

        #map {
            margin-right: 400px;
        }

        #floating-panel {
            background: #fff;
            padding: 5px;
            font-size: 14px;
            font-family: Arial;
            border: 1px solid #ccc;
            box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
            display: none;
        }

        @media print {
            #map {
                height: 500px;
                margin: 0;
            }

            #right-panel {
                float: none;
                width: auto;
            }
        }
    </style>

@section('content')
    <div class="block">
        <h2 class="title is-2">Find Land Routes</h2>
        <h4 class="subtitle is-4">Find land routes between hotels and attractions</h4>
        <h6 class="is-6">Select <b>Starting Point</b> and <b>Ending Point</b> from given menu</h6>
    </div>
    <div class="box" style="height: 80%;">
        <div id="floating-panel" style="width: 15rem;">
            <label class="label" style="margin-bottom: 0">Source</label>
            <div class="select" style="margin-bottom: 1%;">
            <select id="start" style="width:14rem">
                @foreach($hotels as $hotel)
                    @php
                        $pieces = explode(" ", $hotel->name);
                        $first_part = implode(" ", array_splice($pieces, 0, 3));
                    @endphp
                    <option value="{{$hotel->name . ', '. $hotel->city}}">{{$first_part . ', '. $hotel->city}}</option>
                @endforeach
            </select>
            </div>
            <label class="label" style="margin-bottom: 0">Destination</label>
            <div class="select" style="margin-bottom: 1%;">
            <select id="end" style="width:14rem">
                @foreach($attractions as $attraction)
                    @php
                        $pieces1 = explode(" ", $attraction->name);
                        $first_part1 = implode(" ", array_splice($pieces1, 0, 3));
                    @endphp
                    <option value="{{$attraction->name . ', '. $attraction->location}}">{{$first_part1 . ', '. $attraction->location}}</option>
                @endforeach

            </select>
            </div>
            <label class="label" style="margin-bottom: 0">Mode of Travel</label>
            <div class="select" style="margin-bottom: 1%;">
            <select id="mode" style="width:14rem">
                <option value="DRIVING">Driving</option>
                <option value="WALKING">Walking</option>
                <option value="BICYCLING">Bicycling</option>
                <option value="TRANSIT">Transit</option>
            </select>
            </div>
        </div>
        <div id="right-panel"></div>
        <div id="map"></div>
    </div>
    <script>
        function initMap() {
            var directionsDisplay = new google.maps.DirectionsRenderer;
            var directionsService = new google.maps.DirectionsService;
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: 52.519606, lng: 13.406843}
            });
            directionsDisplay.setMap(map);
            directionsDisplay.setPanel(document.getElementById('right-panel'));

            var control = document.getElementById('floating-panel');
            control.style.display = 'block';
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(control);

            var onChangeHandler = function () {
                calculateAndDisplayRoute(directionsService, directionsDisplay);
            };
            document.getElementById('start').addEventListener('change', onChangeHandler);
            document.getElementById('end').addEventListener('change', onChangeHandler);
            document.getElementById('mode').addEventListener('change', onChangeHandler);
        }

        function calculateAndDisplayRoute(directionsService, directionsDisplay) {
            var start = document.getElementById('start').value;
            var end = document.getElementById('end').value;
            var mode = document.getElementById('mode').value;
            directionsService.route({
                origin: start,
                destination: end,
                travelMode: mode
            }, function (response, status) {
                if (status === 'OK') {
                    directionsDisplay.setDirections(response);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{$key}}&callback=initMap">
    </script>

@endsection
