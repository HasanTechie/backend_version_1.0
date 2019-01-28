@extends ('layouts/header')

@section('title',$place->name)
@section('content')

    @if(1 > 2)
        <div class="block">
            <div class="box" style="margin-bottom: 20rem;">
                <h1 class="title">{{$place->name}}</h1>

                <table style="width: 60%;" class="table is-bordered is-striped is-narrow is-hoverable">
                    <tr>
                        <th>place Google ID</th>
                        <td>{{$place->place_id}}</td>
                    </tr>
                    <tr>
                        <th>Ratings</th>
                        <td>{{sprintf("%.1f", $place->rating)}}</td>
                    </tr>
                    <tr>
                        <th>Total Number of Ratings</th>
                        <td>{{$place->total_ratings}}</td>
                    </tr>
                    <tr>
                        <th>place Address</th>
                        <td>{{$place->address}}</td>
                    </tr>
                    <tr>
                        <th>Street Number</th>
                        <td>{{$place->street_number}}</td>
                    </tr>
                    <tr>
                        <th>Route</th>
                        <td>{{$place->route}}</td>
                    </tr>
                    <tr>
                        <th>Sub Locality</th>
                        <td>{{$place->sublocality}}</td>
                    </tr>
                    <tr>
                        <th>Postal Code</th>
                        <td>{{$place->postal_code}}</td>
                    </tr>
                    <tr>
                        <th>Locality</th>
                        <td>{{$place->city}}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{$place->country}}</td>
                    </tr>
                    <tr>
                        <th>Vicnity</th>
                        <td>{{$place->vicinity}}</td>
                    </tr>
                    <tr>
                        <th>Google Maps URL</th>
                        <td><a href="{{$place->maps_url}}">{{$place->maps_url}}</a></td>
                    </tr>
                    <tr>
                        <th>Website URL</th>
                        <td><a href="{{$place->website}}">{{$place->website}}</a></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{$place->phone}}</td>
                    </tr>
                    <tr>
                        <th>International Phone Number</th>
                        <td>{{$place->international_phone}}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>
                            <a href="https://www.google.com/maps/place/{{unserialize($place->geometry)->location->lat}},{{unserialize($place->geometry)->location->lng}}">{{unserialize($place->geometry)->location->lat}}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>
                            <a href="https://www.google.com/maps/place/{{unserialize($place->geometry)->location->lat}},{{unserialize($place->geometry)->location->lng}}">{{unserialize($place->geometry)->location->lng}}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Opening Hours</th>
                        <td>

                            <button type="button" data-html="true" class="btn btn-primary btn-sm" data-toggle="popover"
                                    title="Opening Hours"
                                    data-content="
                                @if($place->opening_hours != 'Not Available')
                                    @foreach(unserialize($place->opening_hours)->weekday_text as $week)
                                    {{$week . "</br>"}}
                                    @endforeach
                                    @endif
                                        ">Opening Hours
                            </button>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <th>Recent Reviews</th>
                        <td>
                            @php
                                $i=0;
                            @endphp
                            @foreach(unserialize($place->reviews) as $review)
                                <button type="button btn-sm" class="btn btn-primary" data-toggle="modal"
                                        data-target="#myModal{{++$i}}">
                                    Review {{$i}}
                                </button>
                                @endforeach
                                </button>
                        </td>
                    </tr>

                </table>
            @php
                $k=0;
            @endphp
            @foreach(unserialize($place->reviews) as $review)
                <!-- The Modal -->
                    <div class="modal fade" id="myModal{{++$k}}">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">{{$review->author_name}}</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <img
                                        src="{{isset($review->profile_photo_url) ? $review->profile_photo_url : ''}}"/> </br> </br>
                                    <b>Ratings </b> : {{sprintf("%.1f", $review->rating)}} </br>
                                    <b>Review </b> : {{$review->text}} </br>
                                    <b>Approx time</b> : {{$review->relative_time_description}} </br>
                                    <b>Date & Time</b> :
                                    @php
                                        $date = new DateTime();
                                        $date->setTimestamp($review->time);
                                        echo $date->format('Y-m-d H:i:s');
                                    @endphp
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach


                <script>
                    $(function () {
                        $('[data-toggle="popover"]').popover();
                    })
                </script>
            </div>

        </div>
    @else
        {{dd(unserialize($place->all_data))}}
    @endif

@endsection
