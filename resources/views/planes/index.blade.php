@extends ('layouts/header')

@section('title','Planes')

@section('content')

    <h1>Planes</h1>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">NAME</th>
            <th scope="col">IATA</th>
            <th scope="col">ICAO</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($planes as $plane)
            <tr>
                <th scope="row">{{$plane->id}}</th>
                <td>{{$plane->name}}</td>
                <td>{{$plane->iata}}</td>
                <td>{{$plane->icao}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
