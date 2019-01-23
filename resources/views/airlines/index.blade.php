@extends ('layouts/header')

@section('title','Airline')

@section('content')

    <h1>Airlines</h1>
    <h2 class="subtitle">Total number of Airlines : <b>{{number_format($airlines->total())}}</b></h2>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">NAME</th>
            <th scope="col">ICAO</th>
            <th scope="col">Callsign</th>
            <th scope="col">Country</th>
            <th scope="col">Active</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($airlines as $airline)
            <tr>
                <th scope="row">{{$airline->uid}}</th>
                <td>{{$airline->name}}</td>
                <td>{{$airline->icao}}</td>
                <td>{{$airline->callsign}}</td>
                <td>{{$airline->country}}</td>
                <td>{{$airline->active}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $airlines->links() }}
@endsection
