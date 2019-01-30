@extends ('layouts/header')

@section('title','Places')

@section('content')


    <h1 class="title is-1">Places</h1>
    <h2 class="subtitle">Total number of Places : <b>{{number_format($places->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>ID</th>
            <th>Place Name</th>
            <th>Address</th>
            <th>City</th>
            <th>Country</th>
            <th>Phone Number</th>
            <th>Website</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>All Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($places as $place)
            <tr>
                <td>{{$place->uid}}</td>
                <td><a href="/places/{{$place->uid}}">{{$place->name}}</a></td>
                <td>{{$place->address}}</td>
                <td>{{$place->city}}</td>
                <td>{{$place->country}}</td>
                <td>{{$place->phone}}</td>
                <td><a href="{{$place->website}}" class="button is-link is-outlined">Website</a></td>
                <td>{{$place->latitude}}</td>
                <td>{{$place->longitude}}</td>
                <td><a href="/places/{{$place->uid}}" class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
            {{ $places->links() }}
        </div>
    </div>
@endsection
