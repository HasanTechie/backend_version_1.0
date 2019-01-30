@extends ('layouts/header')

@section('title','Events')
@section('content')


    <h1 class="title is-1">Events</h1>
    <h2 class="subtitle">Total number of Events : <b>{{number_format($events->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>

            <th>UID</th>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Venue</th>
            <th>Address</th>
            <th>City</th>
            <th>Country</th>
            <th>Website</th>
            <th>
                <nobr>Standard Price</nobr>
            </th>
            <th>Currency</th>

            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($events as $event)
            <tr>
                <td>{{$event->uid}}</td>
                <td>{{$event->id}}</td>
                <td><a href="/events/{{$event->uid}}">{{$event->name}}</a></td>
                <td>{{$event->venue_name}}</td>
                <td>{{$event->venue_address}}</td>
                <td>{{$event->city}}</td>
                <td>{{$event->country}}</td>
                <td><a href="{{$event->url}}" class="button is-link is-outlined">Website</a></td>
                <td><b style="color:#5D7EA7;">{{$event->standard_price_max}}</b></td>
                <td>{{$event->currency}}</td>
                <td><a href="/events/{{$event->uid}}" class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
            {{ $events->links() }}
        </div>
    </div>
@endsection
