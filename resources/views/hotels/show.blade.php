@extends ('layouts/header')

@section('title',$hotel->name)
@section('content')

    @if(1 < 0)
        <div class="block">
            <div class="box" style="margin-bottom: 20rem;">
                <h1 class="title">{{$hotel->name}}</h1>

                <table style="width: 60%;" class="table is-bordered is-striped is-narrow is-hoverable">
                    <tr>
                        <th>Hotel Google ID</th>
                        <td>{{$hotel->hotel_id}}</td>
                    </tr>
                    <tr>
                        <th>Ratings</th>
                        <td>{{sprintf("%.1f", $hotel->rating)}}</td>
                    </tr>
                    <tr>
                        <th>Total Number of Ratings</th>
                        <td>{{$hotel->total_ratings}}</td>
                    </tr>
                    <tr>
                        <th>Hotel Address</th>
                        <td>{{$hotel->address}}</td>
                    </tr>
                    <tr>
                        <th>Street Number</th>
                        <td>{{$hotel->street_number}}</td>
                    </tr>
                    <tr>
                        <th>Route</th>
                        <td>{{$hotel->route}}</td>
                    </tr>
                    <tr>
                        <th>Sub Locality</th>
                        <td>{{$hotel->sublocality}}</td>
                    </tr>
                    <tr>
                        <th>Postal Code</th>
                        <td>{{$hotel->postal_code}}</td>
                    </tr>
                    <tr>
                        <th>Locality</th>
                        <td>{{$hotel->city}}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{$hotel->country}}</td>
                    </tr>
                    <tr>
                        <th>Vicnity</th>
                        <td>{{$hotel->vicinity}}</td>
                    </tr>
                    <tr>
                        <th>Google Maps URL</th>
                        <td><a href="{{$hotel->maps_url}}">{{$hotel->maps_url}}</a></td>
                    </tr>
                    <tr>
                        <th>Website URL</th>
                        <td><a href="{{$hotel->website}}">{{$hotel->website}}</a></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{$hotel->phone}}</td>
                    </tr>
                    <tr>
                        <th>International Phone Number</th>
                        <td>{{$hotel->international_phone}}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>
                            <a href="https://www.google.com/maps/place/{{unserialize($hotel->geometry)->location->lat}},{{unserialize($hotel->geometry)->location->lng}}">{{unserialize($hotel->geometry)->location->lat}}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>
                            <a href="https://www.google.com/maps/place/{{unserialize($hotel->geometry)->location->lat}},{{unserialize($hotel->geometry)->location->lng}}">{{unserialize($hotel->geometry)->location->lng}}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Opening Hours</th>
                        <td>

                            <button type="button" data-html="true" class="btn btn-primary btn-sm" data-toggle="popover"
                                    title="Opening Hours"
                                    data-content="
                                @if($hotel->opening_hours != 'Not Available')
                                    @foreach(unserialize($hotel->opening_hours)->weekday_text as $week)
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
                            @foreach(unserialize($hotel->reviews) as $review)
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
            @foreach(unserialize($hotel->reviews) as $review)
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
        {{dd(json_encode(unserialize($hotel->all_data)))}}
    @endif

@endsection
