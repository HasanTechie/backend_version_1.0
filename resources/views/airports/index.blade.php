<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>Airports</h1>
@foreach ($airports as $airport)
    <div>
        <div>Name: {{$airport->name}}</div>
        <div>ID: {{$airport->id}}</div>
        {{--<div>Airport ID: {{$airport->airport_id}}</div>--}}
        <div>City: {{$airport->city}}</div>
        <div>Country: {{$airport->country}}</div>
        <div>IATA: {{$airport->IATA}}</div>
        <div>ICAO: {{$airport->ICAO}}</div>
        <div>Latitude: {{$airport->latitude}}</div>
        <div>Longitude: {{$airport->longitude}}</div>
        <div>Altitude: {{$airport->altitude}}</div>
        <div>Timezone: {{$airport->timezone}}</div>
        <div>DST: {{$airport->DST}}</div>
        <div>Tz database time zone: {{$airport->Tz_database_time_zone}}</div>
        <div>Type: {{$airport->type}}</div>
        <div>Source: {{$airport->source}}</div>
    </div>

    <br>
@endforeach
</body>
</html>
