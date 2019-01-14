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
            <th>International Phone Number</th>
            <th>City</th>
            <th>Country</th>
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
                <td><a href="/hotels/{{$hotel->id}}">{{$hotel->name}}</a></td>
                <td>{{$hotel->international_phone}}</td>
                <td>
                    <nobr>{{$hotel->city}}</nobr>
                </td>
                <td>
                    <nobr>{{$hotel->country}}</nobr>
                </td>
                <td>{{$hotel->address}}</td>
                <td>@if(!empty($hotel->rating))
                        {{$hotel->rating}}
                    @else
                        {{($hotel->tourpedia_polarity)/2}}
                    @endif
                </td>
                <td>
                    @if(!empty($hotel->total_ratings))
                        {{$hotel->total_ratings}}
                    @else
                        {{$hotel->tourpedia_numReviews}}
                    @endif
                </td>
                <td>{{$hotel->hotel_id}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
    {{ $hotels->links() }}
@endsection
