@extends ('layouts/header')

@section('title','Airline')

@section('content')

    <h1>Flights</h1>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">Flight ID</th>
            <th scope="col">IATA flight_number</th>
            <th scope="col">Airline</th>
            <th scope="col">Arrival Airport Scheduled</th>
            {{--<th scope="col">Arrival Runwaytime Initial</th>--}}
            <th scope="col">Arrival Runwaytime Estimated</th>
            <th scope="col">Departure Airport Scheduled</th>
            {{--<th scope="col">Departure Runwaytime Initial</th>--}}
            <th scope="col">Flight Status</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($json->features as $value)
            <tr>
                <th scope="row">{{$value->id}}</th>
                <td>{{$value->properties->iataFlightNumber}}</td>
                <td>{{$value->properties->airline}}</td>
                <td>{{$value->properties->arrival->aerodrome->scheduled}}</td>
{{--                <td>{{$value->properties->arrival->runwayTime->initial}}</td>--}}
                <td>{{$value->properties->arrival->runwayTime->estimated}}</td>
                <td>{{$value->properties->departure->aerodrome->scheduled}}</td>
{{--                <td>{{$value->properties->departure->runwayTime->initial}}</td>--}}
                <td>{{$value->properties->flightStatus}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
