@extends ('layouts/header')

@section('title','Airports')

@section('content')

    <h1>Airports</h1>
    <h2 class="subtitle">Total number of Airports : <b>{{number_format($airports->total())}}</b></h2>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">NAME</th>
            <th scope="col">City</th>
            <th scope="col">Country</th>
            <th scope="col">IATA</th>
            <th scope="col">ICAO</th>
            <th scope="col">Latitude</th>
            <th scope="col">Longitude</th>
            <th scope="col">Altitude</th>
            <th scope="col">Timezone</th>
            <th scope="col">DST</th>
            <th scope="col">Tz database time zone</th>
            <th scope="col">Type</th>
            <th scope="col">Source</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($airports as $airport)
            <tr>
                <th scope="row">{{$airport->uid}}</th>
                <td>{{$airport->name}}</td>
                <td>{{$airport->city}}</td>
                <td>{{$airport->country}}</td>
                <td>{{$airport->iata}}</td>
                <td>{{$airport->icao}}</td>
                <td>{{$airport->latitude}}</td>
                <td>{{$airport->longitude}}</td>
                <td>{{$airport->altitude}}</td>
                <td>{{$airport->timezone}}</td>
                <td>{{$airport->dst}}</td>
                <td>{{$airport->tz_database_time_zone}}</td>
                <td>{{$airport->type}}</td>
                <td>{{$airport->source}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $airports->links() }}
@endsection
