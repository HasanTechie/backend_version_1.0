@extends ('layouts/header')

@section('title','Routes')

@section('content')

    <h1>Routes</h1>
    <h2 class="subtitle">Total number of routes : <b>{{number_format($routes->total())}}</b></h2>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Airline IATA</th>
            <th scope="col">Source Airport IATA</th>
            <th scope="col">Destination Airport IATA</th>
            <th scope="col">Stops</th>
            <th scope="col">Equipment</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($routes as $route)
            <tr>
                <th scope="row">{{$route->uid}}</th>
                <td>{{$route->airline_iata}}</td>
                <td>{{$route->source_airport_iata}}</td>
                <td>{{$route->destination_airport}}</td>
                <td>{{$route->stops}}</td>
                <td>{{$route->equipment}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $routes->links() }}
@endsection

