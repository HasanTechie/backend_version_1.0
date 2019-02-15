@extends ('layouts/header')

@section('title','Flights Prices')
{{--{{phpinfo()}}--}}
@section('content')


    <h1 class="title is-1">Flights Prices</h1>
    <h2 class="subtitle">total number of records : <b>{{number_format($flights->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>UID</th>
            <th>S No.</th>
            <th>Origin City</th>
            <th>Origin Airport</th>
            <th>Destination City</th>
            <th>Destination Airport</th>
            <th>Departure Date</th>
            <th>Departure Time</th>
            <th>Total Flights Duration</th>
            <th>Number of Total Flights</th>
            <th>Total Price <br/>
                <small>(Adult, Young 14, Child & Infant)</small>
            </th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($flights as $instance)
            <tr>
                <td>{{$instance->uid}}</td>
                <td>{{$instance->s_no}}</td>
                @if ($airport = DB::table('airports')->select('city')->where('IATA', $instance->origin_airport_initial)->first())
                    <td>{{$airport->city}}</td>
                @else
                    <td>Not Available</td>
                @endif
                @if ($airport = DB::table('airports')->select('name')->where('IATA', $instance->origin_airport_initial)->first())
                    <td>{{$airport->name}}</td>
                @else
                    <td>Not Available</td>
                @endif
                @if ($airport = DB::table('airports')->select('city')->where('IATA', $instance->destination_airport_final)->first())
                    <td>{{$airport->city}}</td>
                @else
                    <td>Not Available</td>
                @endif
                @if ($airport = DB::table('airports')->select('name')->where('IATA', $instance->destination_airport_final)->first())
                    <td>{{$airport->name}}</td>
                @else
                    <td>Not Available</td>
                @endif

                @php
                    $arrivalArray = explode("T", unserialize(unserialize($instance->flights_data)[0])->departureDateTime);
                    $arrivalDate = $arrivalArray[0];
                    $arrivalTime = $arrivalArray[1];
                @endphp
                <td>{{$arrivalDate}}</td>
                <td>{{$arrivalTime}}</td>
                <td>{{$instance->flights_duration}}</td>
                <td><a href="/flightsprices/totalflights/{{$instance->uid}}"
                       class="button is-info is-outlined">{{$instance->number_of_flights}}</a></td>
                <td>{{$instance->total_price}}</td>
                <td><a href="/flightsprices/{{$instance->uid}}" class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach

        </tbody>
    </table>
    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
            {{ $flights->links() }}
        </div>
    </div>
@endsection
