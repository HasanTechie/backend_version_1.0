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
            if ($rows > 0) {
                $hotels = DB::table('hotels_hrs')->whereIn('city', ['Berlin', 'Rome'])->limit($rows)->get();
            } else {
                $hotels = DB::table('hotels_hrs')->whereIn('city', ['Berlin', 'Rome'])->get();
            }
            return HotelResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSRoomsPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo)
    {
        if ($apiKey == $this->apiKey) {
            if ($rows > 0) {
                $hotels = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select(DB::raw('hotel_name,  ROUND(avg(price),2) as price, check_in_date, check_out_date'))->where([
                    ['rooms_hrs.hotel_uid', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->limit($rows)->groupBy('check_in_date')->get();
            } else {
                $hotels = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select(DB::raw('hotel_name,  ROUND(avg(price),2) as price, check_in_date, check_out_date'))->where([
                    ['rooms_hrs.hotel_uid', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date')->get();
            }
            return RoomPriceResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSHotelsCompetitorsPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorsuid)
    {
//        foreach($competitorsuid as $instance){
//            dd($instance);
//        }

        $competitorsuidArray = explode(',', str_replace(array('[', ']'), '', $competitorsuid));
//        dd($competitorsuidArray);
        if ($apiKey == $this->apiKey) {
            if ($rows > 0) {

            } else {

                $mainHotel = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select(DB::raw('hotel_name,  ROUND(avg(price),2) as price, check_in_date, check_out_date'))->where([
                    ['rooms_hrs.hotel_uid', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->limit(10)->groupBy('check_in_date')->get();

                foreach ($mainHotel as $hotel) {
                    foreach ($competitorsuidArray as $competitorHotelInstance) {
                        $competitorsData = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select(DB::raw('hotel_name,  ROUND(avg(price),2) as price, check_in_date'))->where([
                            ['rooms_hrs.hotel_uid', '=', $competitorHotelInstance],
                            ['check_in_date', '=', $hotel->check_in_date],
                        ])->groupBy('check_in_date')->get();
                        $dA1['price'] = $competitorsData[0]->price;
                        $dA1['check_in_date'] = $hotel->check_in_date;
                        $dA1['hotel_uid'] = $competitorHotelInstance;
                        $dA1['hotel_name'] = $competitorsData[0]->hotel_name;

                        $hotel->competitorsData[] = $dA1;
                    }
                }

                return CompetitorPriceResource::collection($mainHotel);
            }
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
