@extends ('layouts/header')

@section('title','Hotels')

@section('content')

    <h1>Hotels</h1>

    @foreach ($hotels as $hotel)
        {{$hotel}}
    @endforeach

@endsection
