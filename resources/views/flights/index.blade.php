@extends ('layouts/header')

@section('title','Flights')
{{phpinfo()}}
@section('content')


    <h1>Flights</h1>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>ID</th>
            <th>Flight ID</th>
            <th>IATA flight number</th>
            <th>Airline</th>
            <th>Arrival Airport Scheduled</th>
            <th>Arrival Runwaytime Initial</th>
            <th>Arrival Runwaytime Estimated</th>
            <th>Departure Airport Scheduled</th>
            <th>Departure Runwaytime Initial</th>
            <th>Flight Status</th>
            <th>Aircraft Code</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($flights as $flight)
            <tr>
                <th>{{$flight->id}}</th>
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
