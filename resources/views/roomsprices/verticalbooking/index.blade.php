@extends ('layouts/header')

@section('title','Vertical Booking Hotels Prices')
@section('content')


    <h1 class="title is-1">Vertical Booking Hotels Prices</h1>
    <h2 class="subtitle">total number of records : <b>{{number_format($prices->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>UID</th>
            <th>S No.</th>
            <th>Price</th>
            <th>Room</th>
            <th>Request for (n) persons</th>
            <th>Check In Date</th>
            <th>Check Out Date</th>
            <th>Requested Date</th>
            <th>Hotel Name</th>
            <th>Address</th>
            <th>City</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Website</th>
            <th>Chain Website</th>
            <th>All Details</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($prices as $instance)
            <tr>
                <td>{{$instance->uid}}</td>
                <td>{{$instance->s_no}}</td>
                <td>{{$instance->display_price}}</td>
                <td>{{$instance->room}}</td>
                <td>room for {{$instance->number_of_adults_in_room_request}} person(s)</td>
                <td>{{$instance->check_in_date}}</td>
                <td>{{$instance->check_out_date}}</td>
                <td>{{$instance->request_date}}</td>
                <td>
                    <a href="https://www.google.com/search?q={{$instance->hotel_name}}, {{$instance->hotel_city}}">{{$instance->hotel_name}}</a>
                </td>
                <td>{{$instance->hotel_address}}</td>
                <td>{{$instance->hotel_city}}</td>
                <td>{{$instance->hotel_phone}}</td>
                <td>{{$instance->hotel_email}}</td>
                <td>{{$instance->hotel_website}}</td>
                <td>{{$instance->chain_website}}</td>
                <td><a href="/roomsprices/verticalbooking/{{$instance->uid}}" class="button is-primary is-outlined">Details</a>
                </td>
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
