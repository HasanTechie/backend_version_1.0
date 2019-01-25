@extends ('layouts/header')

@section('title','Events')
@section('content')


    <h1 class="title is-1">Events</h1>
    <h2 class="subtitle">Total number of Events : <b>{{number_format($events->total())}}</b></h2>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>UID</th>
            <th>Event Name</th>
            <th>Event ID</th>
            <th>Website</th>
            <th>Standard Price</th>
            <th>Standard Price including Fees</th>
            <th>Source</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($events as $event)
            <tr>
                <td>{{$event->uid}}</td>
                <td><a href="/events/{{$event->uid}}">{{$event->name}}</a></td>
                <td>{{$event->id}}</td>
                <td><a href="{{$event->url}}" class="button is-link is-outlined">Website</a></td>
                <td><b style="color:#5D7EA7;">{{$event->standard_price}}</b></td>
                <td><b style="color:#5D7EA7;">{{$event->standard_price_including_fees}}</b></td>
                <td><a href="/events/{{$event->uid}}" class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $events->links() }}
@endsection
