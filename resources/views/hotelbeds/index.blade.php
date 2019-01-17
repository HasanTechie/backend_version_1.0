@extends ('layouts/header')

@section('title','New Hotels')
{{--{{phpinfo()}}--}}
@section('content')


    <h1 class="title is-1">New Hotels</h1>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Country Code</th>
            <th>Destination Code</th>
            <th>Address</th>
            <th>City</th>
            <th>website</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($hotelbeds as $hotelbed)
            <tr>

                <td>{{$hotelbed->id}}</td>
                <td><a href="/hotelbeds/{{$hotelbed->id}}">{{$hotelbed->name}}</a></td>
                <td>{{$hotelbed->country_code}}</td>
                <td>
                    <nobr>{{$hotelbed->destination_code}}</nobr>
                </td>
                <td>
                    <nobr>{{$hotelbed->address}}</nobr>
                </td>
                <td>{{$hotelbed->city}}</td>
                <td>{{$hotelbed->website}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
    {{ $hotelbeds->links() }}
@endsection
