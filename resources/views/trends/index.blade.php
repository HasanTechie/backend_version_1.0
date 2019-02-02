@extends ('layouts/header')

@section('title','Trends')

@section('content')

    <h1>Trends</h1>
    <h2 class="subtitle">Total number of trends records : <b>{{number_format($trends->total())}}</b></h2>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">UID</th>
            <th scope="col">S No.</th>
            <th scope="col">Keyword</th>
            <th scope="col">Language</th>
            <th scope="col">Country</th>
            <th scope="col">Time</th>
            <th scope="col">All Details</th>
            <th scope="col">Interest over time</th>
            <th scope="col">Interest by subregion</th>
            <th scope="col">Related topics</th>
            <th scope="col">Related queries</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($trends as $trend)
            <tr>
                <th scope="row">{{$trend->uid}}</th>
                <td>{{$trend->s_no}}</td>
                <td>{{$trend->keyword}}</td>
                <td>{{$trend->keyword_language}}</td>
                <td>{{$trend->country_name}}</td>
                <td>@if($trend->time == 'today 5-y') {{'5 years data'}} @endif</td>
                <td><a href="/trends/{{$trend->uid}}" class="button is-primary is-outlined">Details</a></td>
                <td><a href="/trends/interestovertime/{{$trend->uid}}"
                       class="button is-primary is-outlined">Details</a></td>
                <td><a href="/trends/interestbysubregion/{{$trend->uid}}" class="button is-primary is-outlined">Details</a>
                </td>
                <td><a href="/trends/relatedtopics/{{$trend->uid}}"
                       class="button is-primary is-outlined">Details</a></td>
                <td><a href="/trends/relatedqueries/{{$trend->uid}}"
                       class="button is-primary is-outlined">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="block" align="center" style="margin-bottom: 2.5rem;">
        <div class="box" style="width: 50%;">
            {{ $trends->links() }}
        </div>
    </div>
@endsection

