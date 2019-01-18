@extends ('layouts/header')

@section('title','Hotels')
{{--{{phpinfo()}}--}}
@section('content')


    <h1 class="title is-1">Hotels</h1>
    <h2 class="subtitle">Total number of Hotels : <b>{{number_format($hotels->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>ID</th>
            <th>Hotel Name</th>
            <th>Hotel Address</th>
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
        @foreach ($hotels as $hotel)
            <tr>

                <td>{{$hotel->id}}</td>
                <td><a href="/hotels/{{$hotel->id}}">{{$hotel->name}}</a></td>
                <td>{{$hotel->address}}</td>
                <td>{{$hotel->city}}</td>
                <td>{{$hotel->country}}</td>
                <td>{{$hotel->phone}}</td>
                <td><a href="{{$hotel->website}}" class="button is-link is-outlined">Website</a></td>
                <td>{{$hotel->latitude}}</td>
                <td>{{$hotel->longitude}}</td>
                <td><a href="/hotels/{{$hotel->id}}" class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $hotels->links() }}
@endsection
