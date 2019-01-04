@extends ('layouts/header')

@section('title','Flights')
{{--{{phpinfo()}}--}}
@section('content')


    <h1>Flights</h1>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Flight ID</th>
            <th scope="col">IATA flight_number</th>
            <th scope="col">Airline</th>
            <th scope="col">Arrival Airport Scheduled</th>
            <th scope="col">Arrival Runwaytime Initial</th>
            <th scope="col">Arrival Runwaytime Estimated</th>
            <th scope="col">Departure Airport Scheduled</th>
            <th scope="col">Departure Runwaytime Initial</th>
            <th scope="col">Flight Status</th>
            <th scope="col">Aircraft Code</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($flights as $flight)
            <tr>
                <th scope="row">{{$flight->id}}</th>
                <td>{{$flight->flight_id}}</td>
                <td>{{$flight->iata_flight_number}}</td>
                <td>{{$flight->airline}}</td>
                <td>{{$flight->arrival_airport_scheduled}}</td>
                <td>{{$flight->arrival_runway_time_initial}}</td>
                <td>{{$flight->arrival_runway_time_estimated}}</td>
                <td>{{$flight->departure_airport_scheduled}}</td>
                <td>{{$flight->departure_runway_time_initial}}</td>
                <td>{{$flight->flight_status}}</td>
                <td>{{$flight->aircraft_code}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
