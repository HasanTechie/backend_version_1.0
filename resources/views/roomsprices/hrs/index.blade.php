@extends ('layouts/header')

@section('title','HRS Rooms')
@section('content')

    @if(isset($rooms[0]->hotel_name))

        <h1 class="title is-1">{{$rooms[0]->hotel_name}} Rooms</h1>
        <h2 class="subtitle">total number of records : <b>{{number_format($rooms->total())}}</b></h2>
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>UID</th>
                <th>S No.</th>
                <th>Room</th>
                <th>Room Type</th>
                <th>Room Criteria</th>
                <th>Room Short Description</th>
                <th>Room All Details</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($rooms as $instance)
                <tr>
                    <td>{{$instance->uid}}</td>
                    <td>{{$instance->s_no}}</td>
                    <td>{{$instance->room}}</td>
                    <td>{{$instance->room_type}}</td>
                    <td>{{$instance->criteria}}</td>
                    <td>{{$instance->short_description}}</td>
                    <td><a href="/roomsrooms/hrs/{{$instance->hotel_uid}}/{{$instance->uid}}"
                           class="button is-primary is-outlined">Details</a></td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="block" align="center" style="margin-bottom: 2.5rem;">
            <div class="box" style="width: 50%;">
                {{ $rooms->links() }}
            </div>
        </div>
    @else
        <h2>{{'No Rooms Available for this Hotel'}}</h2>
    @endif

@endsection
