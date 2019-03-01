@extends ('layouts/header')

@section('title','Eurobookings Prices')
@section('content')


    <h1 class="title is-1">{{$prices[0]->hotel_name}} Prices</h1>
    <h2 class="subtitle">total number of records : <b>{{number_format($prices->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>UID</th>
            <th>S No.</th>
            <th>Price</th>
            <th>Currency</th>
            <th>Room</th>
            <th>Request for (n) persons</th>
            <th>Check In Date</th>
            <th>Check Out Date</th>
            <th>Requested Date</th>
            <th>Room All Details</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($prices as $instance)
            <tr>
                <td>{{$instance->uid}}</td>
                <td>{{$instance->s_no}}</td>
                <td>{{$instance->price}}</td>
                <td>{{$instance->currency}}</td>
                <td>{{$instance->room}}</td>
                <td>room for {{$instance->number_of_adults_in_room_request}} person(s)</td>
                <td>{{$instance->check_in_date}}</td>
                <td>{{$instance->check_out_date}}</td>
                <td>{{$instance->request_date}}</td>
                <td><a href="/roomsprices/eurobookings/{{$instance->hotel_uid}}/{{$instance->uid}}"
                       class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach

        </tbody>
    </table>
    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
            {{ $prices->links() }}
        </div>
    </div>
@endsection
