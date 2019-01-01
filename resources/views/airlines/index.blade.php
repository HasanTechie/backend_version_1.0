@extends ('/layouts/header')

@section('title','Airline')

@section('content')

    <h1>Airports</h1>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">NAME</th>
            <th scope="col">Alias</th>
            <th scope="col">IATA</th>
            <th scope="col">ICAO</th>
            <th scope="col">Callsign</th>
            <th scope="col">Country</th>
            <th scope="col">Active</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($airlines as $airline)
            <tr>
                <th scope="row">{{$airline->id}}</th>
                <td>{{$airline->name}}</td>
                <td>{{$airline->alias}}</td>
                <td>{{$airline->IATA}}</td>
                <td>{{$airline->ICAO}}</td>
                <td>{{$airline->callsign}}</td>
                <td>{{$airline->country}}</td>
                <td>{{$airline->active}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
