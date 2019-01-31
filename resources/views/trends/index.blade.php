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
        @foreach ($trends as $twoTrends)
            <tr>
                <th scope="row">{{$twoTrends->uid}}</th>
                <td>{{$twoTrends->s_no}}</td>
                <td>{{$twoTrends->keyword}}</td>
                <td>{{$twoTrends->keyword_language}}</td>
                <td>{{$twoTrends->country_name}}</td>
                <td>{{$twoTrends->time}}</td>
                <td><a href="/trends/{{$twoTrends->uid}}" class="button is-primary is-outlined">Details</a></td>
                <td><a href="/trends/interestovertime/{{$twoTrends->uid}}" class="button is-primary is-outlined">Details</a></td>
                <td><a href="/trends/interestbysubregion/{{$twoTrends->uid}}" class="button is-primary is-outlined">Details</a></td>
                <td><a href="/trends/relatedtopics/{{$twoTrends->uid}}" class="button is-primary is-outlined">Details</a></td>
                <td><a href="/trends/relatedqueries/{{$twoTrends->uid}}" class="button is-primary is-outlined">Details</a></td>
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

