@extends ('layouts/header')

@section('title','Weather')

@section('content')

    <h1>Weather</h1>
    <h2 class="subtitle">Total number of weathers records : <b>{{number_format($weathers->total())}}</b></h2>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">UID</th>
            <th scope="col">S No.</th>
            <th scope="col">City</th>
            <th scope="col">Country</th>
            <th scope="col">Latitude</th>
            <th scope="col">Longitude</th>
            <th scope="col">All Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($weathers as $weather)
            <tr>
                <th scope="row">{{$weather->uid}}</th>
                <td>{{$weather->s_no}}</td>
                <td>{{$weather->city}}</td>
                <td>{{$weather->country}}</td>
                <td>{{$weather->latitude}}</td>
                <td>{{$weather->longitude}}</td>
                <td><a href="/weathers/{{$weather->uid}}" class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
            {{ $weathers->links() }}
        </div>
    </div>
@endsection

