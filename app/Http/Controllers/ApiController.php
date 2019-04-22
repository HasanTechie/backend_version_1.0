<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompetitorPrice as CompetitorPriceResource;
use App\Http\Resources\Hotel as HotelResource;
use App\Http\Resources\RoomPrice as RoomPriceResource;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW';
//        $this->middleware('auth:admin');
    }

    public function HRSHotels($rows, $apiKey)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('hotels_hrs')->whereIn('city',['Berlin','Rome'])->limit($rows)->get();
            return HotelResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSRoomsPrices($rows, $hotel, $dateFrom, $dateTo, $apiKey)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select(DB::raw('avg(price) as price, check_in_date'))->where([
                ['rooms_hrs.hotel_uid', '=', $hotel],
                ['check_in_date', '>=', $dateFrom],
                ['check_in_date', '<=', $dateTo],
            ])->limit($rows)->groupBy('check_in_date')->get();

            return RoomPriceResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSHotelsCompetitorsPrices($rows, $hotel, $dateFrom, $dateTo, $apiKey)
    {
        /*if ($apiKey == $this->apiKey) {
            $dates = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select('check_in_date')->distinct('check_in_date')->where([
                ['hotel_uid', '=', $hotel],
                ['check_in_date', '>=', $dateFrom],
                ['check_in_date', '<=', $dateTo],
            ])->orderBy('check_in_date')->get();
            $competitorRooms = [];
            foreach ($dates as $date) {

                $CompetitorHotels = DB::table('hotels_competitors')->where('hotel_uid', '=', $hotel)->get();

                foreach ($CompetitorHotels as $competitorHotel) {
                    $competitorRooms = DB::table('rooms_prices_eurobookings_data')->where([
                        ['check_in_date', '=', $date->check_in_date],
                        ['hotel_uid', '=', $competitorHotel->hotel_competitor_uid],
                    ])->get();
                }
            }
            return CompetitorPriceResource::collection($competitorRooms);
        } else {
            dd('Error: Incorrect API Key');
        }*/
    }
}
