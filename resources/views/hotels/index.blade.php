@extends ('layouts/header')

@section('title','Hotels')
{{--{{phpinfo()}}--}}
@section('content')


    <h1 class="title is-1">Hotels</h1>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>ID</th>
            <th>Hotel Name</th>
            <th>City Name</th>
            <th>Hotel Address</th>
            <th>Rating</th>
            <th>Total Rating</th>
            <th>Hotel Google ID</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($hotels as $hotel)
            <tr>

                <td>{{$hotel->id}}</td>
                <td>{{$hotel->name}}</td>
                @if(strpos($hotel->address,'Berlin') !== false)
                    <td>{{'Berlin'}}</td>
                @elseif(strpos($hotel->address,'Hamburg') !== false)
                    <td>{{'Hamburg'}}</td>
                @elseif(strpos($hotel->address,'München') !== false)
                    <td>{{'München'}}</td>
                @elseif(strpos($hotel->address,'Köln') !== false)
                    <td>{{'Köln'}}</td>
                @elseif(strpos($hotel->address,'Frankfurt am Main') !== false)
                    <td>{{'Frankfurt am Main'}}</td>
                @else
                    <td></td>
                @endif
                <td>{{$hotel->address}}</td>
                <td>{{$hotel->rating}}</td>
                <td>{{$hotel->total_ratings}}</td>
                <td>{{$hotel->hotel_id}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection
