<?php

namespace App\Http\Controllers;

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

    public function HRSHotels($row, $apiKey)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('hotels_hrs')->limit($row)->get();
            return HotelResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSRoomsPrices($row, $hotel, $dateFrom, $dateTo, $apiKey)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select(DB::raw('avg(price) as price, check_in_date'))->where([
                ['rooms_hrs.hotel_uid', '=', $hotel],
                ['check_in_date', '>=', $dateFrom],
                ['check_in_date', '<=', $dateTo],
            ])->limit($row)->groupBy('check_in_date')->get();

            return RoomPriceResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
