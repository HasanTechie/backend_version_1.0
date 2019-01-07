@extends ('layouts/header')

@section('title','Flights')
{{--{{phpinfo()}}--}}
@section('content')


    <h1 class="title is-1">Flights & Passengers in selected route</h1>

    <div class="block">
        <form method="POST" actio="/flights/search/">
            @csrf
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Airport A</label>
                </div>
                <div class="field-body">
                    <div class="field is-narrow has-addons">
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                {{--                                {{dd($flights[0]->arrival_airport_scheduled)}}--}}
                                @if($airports = DB::table('airports')->select('id','name','city','icao','country','icao')->get())

                                    <select name="icao_a" required>
                                        <option value="">Select Airport A</option>
                                        @foreach($airports as $airport)
                                            @if(isset($flights[0]->departure_airport_scheduled))
                                                @if($flights[0]->departure_airport_scheduled == $airport->icao && $is_submitted==1)
                                                    <option value="{{$airport->icao}}"
                                                            selected>{{$airport->name.', '.$airport->city.', '.$airport->country}}</option>
                                                @else
                                                    <option
                                                        value="{{$airport->icao}}">{{$airport->name.', '.$airport->city.', '.$airport->country}}</option>
                                                @endif
                                            @else
                                                <option
                                                    value="{{$airport->icao}}">{{$airport->name.', '.$airport->city.', '.$airport->country}}</option>
                                            @endif
                                        @endforeach
                                        @endif
                                    </select>
                            </div>
                            <span class="icon is-small is-left">
                              <i class="fas fa-plane"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Airport B</label>
                </div>
                <div class="field-body">
                    <div class="field is-narrow">
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select name="icao_b" required>
                                    <option value="">Select Airport B</option>
                                    @foreach($airports as $airport)
                                        @if(isset($flights[0]->arrival_airport_scheduled))
                                            @if($flights[0]->arrival_airport_scheduled == $airport->icao && $is_submitted==1)
                                                <option value="{{$airport->icao}}"
                                                        selected>{{$airport->name.', '.$airport->city.', '.$airport->country}}</option>
                                            @else
                                                <option
                                                    value="{{$airport->icao}}">{{$airport->name.', '.$airport->city.', '.$airport->country}}</option>
                                            @endif
                                        @else
                                            <option
                                                value="{{$airport->icao}}">{{$airport->name.', '.$airport->city.', '.$airport->country}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <span class="icon is-small is-left">
                              <i class="fas fa-plane"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    <label class="label">Criteria</label>
                </div>
                <div class="field-body">
                    <div class="field is-narrow">
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="criteria" value="departure">
                                Source to Destination (departures only)
                            </label>
                            <label class="radio">
                                <input type="radio" name="criteria" value="arrival">
                                Destination to Source (arrivals only)
                            </label>
                            <label class="radio">
                                <input type="radio" name="criteria" value="all" checked>
                                All
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    <label class="label">Flight Status</label>
                </div>
                <div class="field-body">
                    <div class="field is-narrow">
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="flight_status" value="completed">
                                Completed
                            </label>
                            <label class="radio">
                                <input type="radio" name="flight_status" value="airborne">
                                Airborne
                            </label>
                            <label class="radio">
                                <input type="radio" name="flight_status" value="scheduled">
                                Scheduled
                            </label>
                            <label class="radio">
                                <input type="radio" name="flight_status" value="all" checked>
                                All
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    <label class="label">Include private airplanes?</label>
                </div>
                <div class="field-body">
                    <div class="field is-narrow">
                        <div class="control">
                            <label class="radio">
                                <input type="radio" value="yes" name="is_private_included">
                                Yes
                            </label>
                            <label class="radio">
                                <input type="radio" value="no" name="is_private_included" checked>
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    <!-- Left empty for spacing -->
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" name="submit">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <h2 class="subtitle">Total number of selected flights : <b>{{number_format($flights->total())}}</b></h2>
    <h2 class="subtitle">Total number of Passengers in these flights : <b>{{number_format($total_capacity)}}</b></h2>

    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>Flight Number</th>
            <th>Departure Airport Scheduled</th>
            <th>Airline</th>
            <th>Arrival Airport Scheduled</th>
            <th>Arrival Runwaytime Estimated</th>
            <th>Departure Runwaytime Initial</th>
            <th>Flight Status</th>
            <th>Aircraft Name</th>
            <th>Aircraft Capacity</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($flights as $flight)
            <tr>
                <td>
                    <a href="https://www.google.com/search?q={{$flight->iata_flight_number}} flight">{{$flight->iata_flight_number}}</a>
                </td>
                @if ($airport = DB::table('airports')->select('name')->where('ICAO', $flight->departure_airport_scheduled)->first())
                    <td>{{$airport->name}}</td>
                @else
                    <td>Not Available</td>
                @endif
                @if ($airline = DB::table('airlines')->select('name')->where('ICAO', $flight->airline)->first())
                    <td>{{$airline->name}}</td>
                @else
                    <td>Not Available</td>
                @endif
                @if ($airport = DB::table('airports')->select('name')->where('ICAO', $flight->arrival_airport_scheduled)->first())
                    <td>{{$airport->name}}</td>
                @else
                    <td>Not Available</td>
                @endif
                <td>{{$flight->arrival_runway_time_estimated_date}}
                    <br/>{{isset($flight->arrival_runway_time_estimated_time) ? $flight->arrival_runway_time_estimated_time : '' }}
                </td>
                <td>{{isset($flight->departure_runway_time_initial_date) ? $flight->departure_runway_time_initial_date : '&nbsp;' }}
                    <br/>{{isset($flight->departure_runway_time_initial_time) ? $flight->departure_runway_time_initial_time : '' }}
                </td>
                <td>{{$flight->flight_status}}</td>
                <td>{{$flight->name}}</td>
                <td>{{$flight->capacity}} passengers</td>
            </tr>
        @endforeach

        </tbody>
    </table>
    {{ $flights->links() }}
@endsection
