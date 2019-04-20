@extends ('layouts/header')

@section('title','Eurobookings Hotels')
@section('content')


    <h1 class="title is-1">Eurobookings Hotels</h1>
    <h2 class="subtitle">Total number of Hotels : <b>{{number_format($hotels->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>ID</th>
            <th>Hotel Name</th>
            <th>Hotel Address</th>
            <th>City</th>
            <th>Country</th>
            <th>TripAdvisor Ratings</th>
            <th>TripAdvisor Total Ratings</th>
            <th>Hotel Info</th>
            <th>Reviews on TripAdvisor</th>
            <th>Hotel Detail</th>
            <th>Hotel Facilities</th>
            <th>All Details</th>
            <th>Rooms Prices</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($hotels as $hotel)
            <tr>
                <td>{{$hotel->uid}}</td>
                <td><a href="https://www.google.com/search?q={{$hotel->name}}, {{$hotel->city}}">{{$hotel->name}}</a>
                </td>
                <td>{{$hotel->address}}</td>
                <td>{{$hotel->city}}</td>
                <td>{{$hotel->country_code}}</td>
                <td>{{$hotel->ratings_on_tripadvisor}}</td>
                <td>{{$hotel->total_number_of_ratings_on_tripadvisor}}</td>
                <td><a href="eurobookings/hotelinfo/{{$hotel->uid}}" class="button is-info is-outlined">O</a></td>
                <td><a href="eurobookings/reviewsontripadvisor/{{$hotel->uid}}" class="button is-info is-outlined">O</a>
                </td>
                <td><a href="eurobookings/details/{{$hotel->uid}}" class="button is-info is-outlined">O</a></td>
                <td><a href="eurobookings/facilities/{{$hotel->uid}}" class="button is-info is-outlined">O</a></td>
                <td><a href="eurobookings/{{$hotel->uid}}" class="button is-primary is-outlined">O</a></td>
                <td><a href="eurobookings/roomsprices/{{$hotel->uid}}" class="button is-success is-outlined">Rooms
                        Prices</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
            {{ $hotels->links() }}
        </div>
    </div>
@endsection
