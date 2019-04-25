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
            $hotels = DB::table('hotels_hrs')
                ->whereIn('city', ['Berlin', 'Rome']);
            ($rows > 0) ? $hotels = $hotels->limit($rows) : null;
            $hotels = $hotels->get();

            return HotelResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSRoomsPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo)
    {
        if ($apiKey == $this->apiKey) {
            $prices = DB::table('rooms_hrs')
                ->select(DB::raw('rooms_hrs.id, hotels_hrs.name as hotel_name, hotel_id,  ROUND(avg(price),2) as price, check_in_date'))
                ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                ['rooms_hrs.hotel_id', '=', $hotel],
                ['check_in_date', '>=', $dateFrom],
                ['check_in_date', '<=', $dateTo],
            ])->groupBy('check_in_date');
            ($rows > 0) ? $prices = $prices->limit($rows) : null;
            $prices = $prices->get();

            return RoomPriceResource::collection($prices);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSHotelsCompetitorsPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorsid)
    {
        $competitorsidArray = explode(',', str_replace(array('[', ']'), '', $competitorsid));

        if ($apiKey == $this->apiKey) {
            $prices = DB::table('rooms_hrs')
                ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($rows > 0) ? $prices = $prices->limit($rows) : null;
            $prices = $prices->get();

            foreach ($prices as $hotel) {
                foreach ($competitorsidArray as $competitorHotelInstance) {
                    $competitorsData = DB::table('rooms_hrs')
                        ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                        ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                        ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                        ->where([
                        ['rooms_hrs.hotel_id', '=', $competitorHotelInstance],
                        ['check_in_date', '=', $hotel->check_in_date],
                    ])->groupBy('check_in_date')->get();
                    if (count($competitorsData) > 0) {
                        $dA1['price'] = $competitorsData[0]->price;
                        $dA1['check_in_date'] = $hotel->check_in_date;
                        $dA1['hotel_id'] = $competitorHotelInstance;
                        $dA1['hotel_name'] = $competitorsData[0]->hotel_name;
                    }

                    $dA2[] = isset($dA1) ? $dA1 : null;
                    $dA1 = null;
                }
                $hotel->competitorsData = array_filter($dA2);
                $dA2 = null;
            }

            return CompetitorPriceResource::collection($prices);

        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
