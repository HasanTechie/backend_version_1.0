<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>hotels</h1>
@foreach ($hotels as $hotel)
    {{$hotel->id}}
@endforeach

</body>
</html>
