@extends ('layouts/header')

@section('title','Routes')

@section('content')

    <h1>Routes</h1>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Airline</th>
            <th scope="col">Airline ID</th>
            <th scope="col">Source Airport</th>
            <th scope="col">Source Airport ID</th>
            <th scope="col">Destination Airport</th>
            <th scope="col">Destination Airport ID</th>
            <th scope="col">Codeshare</th>
            <th scope="col">Stops</th>
            <th scope="col">Equipment</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($routes as $route)
            <tr>
                <th scope="row">{{$route->id}}</th>
                <td>{{$route->airline}}</td>
                <td>{{$route->airline_id}}</td>
                <td>{{$route->source_airport}}</td>
                <td>{{$route->source_airport_id}}</td>
                <td>{{$route->destination_airport}}</td>
                <td>{{$route->destination_airport_id}}</td>
                <td>{{$route->codeshare}}</td>
                <td>{{$route->stops}}</td>
                <td>{{$route->equipment}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

