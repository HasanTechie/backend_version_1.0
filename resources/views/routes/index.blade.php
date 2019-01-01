<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>Routes</h1>
@foreach ($routes as $route)
    <div>
        <div>ID: {{$route->id}}</div>
        <div>Airline: {{$route->airline}}</div>
        <div>Airline ID: {{$route->airline_id}}</div>
        <div>Source Airport: {{$route->source_airport}}</div>
        <div>Source Airport ID: {{$route->source_airport_id}}</div>
        <div>Destination Airport: {{$route->destination_airport}}</div>
        <div>Destination Airport ID: {{$route->destination_airport_id}}</div>
        <div>Codeshare: {{$route->codeshare}}</div>
        <div>Stops: {{$route->stops}}</div>
        <div>Equipment: {{$route->equipment}}</div>
    </div>

    <br>
@endforeach
</body>
</html>
