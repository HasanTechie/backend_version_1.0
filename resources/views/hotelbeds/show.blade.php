@extends ('layouts/header')

@section('title',$hotelbed[0]->name)
@section('content')

        {{dd(unserialize($hotelbed[0]->all_data))}}

@endsection
