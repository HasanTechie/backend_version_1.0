<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>Airports</h1>
@foreach ($content as $line)
    {{$line}} <br> <br>
@endforeach
{{--{{$content}}--}}
</body>
</html>
