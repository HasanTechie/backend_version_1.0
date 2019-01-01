<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>Airlines</h1>
@foreach ($airlines as $airline)
    <div>
        <div>Name: {{$airline->name}}</div>
        <div>ID: {{$airline->id}}</div>
        <div>ID: {{$airline->airline_id}}</div>
        <div>Alias: {{$airline->alias}}</div>
        <div>IATA: {{$airline->IATA}}</div>
        <div>ICAO: {{$airline->ICAO}}</div>
        <div>Callsign: {{$airline->callsign}}</div>
        <div>Country: {{$airline->country}}</div>
        <div>Active: {{$airline->active}}</div>

    </div>

    <br>
@endforeach
</body>
</html>
