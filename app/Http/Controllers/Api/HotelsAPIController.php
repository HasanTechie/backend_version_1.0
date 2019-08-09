<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Hotel as HotelResource;
use App\Http\Resources\RoomPrice as RoomPriceResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class HotelsAPIController extends Controller
{
    //
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW';
    }

    public function HRSHotels($rows, $apiKey, $countryCode)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('hotels_hrs');
            if ($countryCode == 'All') {
//                $hotels = $hotels->whereIn('city', ['Rome', 'Berlin']);
            } else {
                $hotels = $hotels->where('country_code', '=', $countryCode);
            }
            ($rows > 0) ? $hotels = $hotels->limit($rows) : null;
            $hotels = $hotels->get();
            if (isset($hotels)) {
                return HotelResource::collection($hotels);
            }
            dd('Error: Data Not Found :  HRSHotels');
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSRoomsPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo)
    {
        if ($apiKey == $this->apiKey) {
            $prices = DB::table('rooms_hrs')
                ->select(DB::raw('rooms_hrs.id, hotels_hrs.name as hotel_name, room, hotel_id,  ROUND(avg(price),2) as price, check_in_date'))
                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($rows > 0) ? $prices = $prices->limit($rows) : null;
            $prices = $prices->get();
            if (isset($prices)) {
                return RoomPriceResource::collection($prices);
            }
            dd('Error: Data Not Found :  HRSRoomsPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
