<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>Planes</h1>

@foreach ($planes as $plane)
    <div>
        <div>NAME: {{$plane->name}}</div>
        <div>ID: {{$plane->id}}</div>
        <div>IATA: {{$plane->IATA}}</div>
        <div>ICAO: {{$plane->ICAO}}</div>
    </div>

    <br>
@endforeach
</body>
</html>
