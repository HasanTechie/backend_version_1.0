<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SoliDPS</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    {{--    @if (Route::has('login'))--}}
    {{--        <div class="top-right links">--}}
    {{--            @auth--}}
    {{--                <a href="{{ url('/home') }}">Home</a>--}}
    {{--            @else--}}
    {{--                <a href="{{ route('login') }}">Login</a>--}}

    {{--                @if (Route::has('register'))--}}
    {{--                    <a href="{{ route('register') }}">Register</a>--}}
    {{--                @endif--}}
    {{--            @endauth--}}
    {{--        </div>--}}
    {{--    @endif--}}

    <div class="content">
        <div class="title m-b-md">
            SoliDPS
        </div>

        <div class="links">
            <a href="/hotels/eurobookings">Eurbookings Prices </a>
            <a href="/hotels/hrs">HRS Prices </a>
            <a href="/trends">Google Trends</a>
        </div>
        <br/>
        <div class="links">
            <a href="/roomsprices/verticalbooking">Hotel VerticalBooking Prices </a>
            <a href="/roomsprices/hotel1">Baglioni Hotels Prices </a>
            <a href="/roomsprices/hotel3">Novecento Hotel Prices </a>

        </div>

        <div class="links">
            <a href="/landroutes">Search LandRoutes</a>
            <a href="/roomsprices/hotel2">Hotel EmonaAquaeductus Prices </a>
            <a href="/weathers">Weather</a>


        </div>
        <br/>
        <br/>

        <div class="links">
            <a href="/flightsprices">Airfrance KLM Flights</a>
            <a href="/flights/search">Search Flights</a>


        </div>

        <div class="links">
            <a href="/events">Events</a>
            <a href="/hotelsbasicdata">Hotels</a>
        </div>
        <br/>
        <br/>

        <div class="links">
            <a href="/flights">Laminar API Flights</a>
            <a href="/places/">Places</a>
            <a href="/airports">Airports</a>

        </div>
        <div class="links">

            <a href="/airlines">Airlines</a>
            <a href="/planes">Planes</a>
            <a href="/routes">Plane Routes</a>

        </div>
    </div>
</div>
</body>
</html>
