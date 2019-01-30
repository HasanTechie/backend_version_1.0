@extends ('layouts/header')

@section('title','Planes')

@section('content')

    <h1>Planes</h1>
    <h2 class="subtitle">Total number of planes : <b>{{number_format($planes->total())}}</b></h2>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">NAME</th>
            <th scope="col">IATA</th>
            <th scope="col">ICAO</th>
            <th scope="col">Passenger Capacity</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($planes as $plane)
            <tr>
                <th scope="row">{{$plane->uid}}</th>
                <td>{{$plane->name}}</td>
                <td>{{$plane->iata}}</td>
                <td>{{$plane->icao}}</td>
                <td>{{$plane->capacity}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
    {{ $planes->links() }}
        </div>
    </div>
@endsection
