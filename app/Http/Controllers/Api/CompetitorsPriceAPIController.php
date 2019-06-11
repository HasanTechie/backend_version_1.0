<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompetitorAvgPrice as CompetitorAvgPriceResource;
use App\Http\Resources\CompetitorPriceApex as CompetitorPriceResourceApex;
use App\Http\Resources\CompetitorRoomPrice as CompetitorRoomPriceResource;
use App\Http\Resources\CompetitorRoomAvgPrice as CompetitorRoomAvgPriceResource;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CompetitorsPriceAPIController extends Controller
{
    //
    public function HRSHotelsCompetitorsAvgPricesOld($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIdsArray = explode(',', $competitorIds);
            $prices = DB::table('rooms_hrs')
                ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
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
                foreach ($prices as $hotelPrice) {
                    foreach ($competitorIdsArray as $competitorHotelInstance) {
                        $competitorsData = DB::table('rooms_hrs')
                            ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                            ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                            ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                            ->where([
                                ['rooms_hrs.hotel_id', '=', $competitorHotelInstance],
                                ['check_in_date', '=', $hotelPrice->check_in_date],
                            ])->groupBy('check_in_date')->get();
                        if (count($competitorsData) > 0) {
                            $dA1['price'] = $competitorsData[0]->price;
                            $dA1['check_in_date'] = $hotelPrice->check_in_date;
                            $dA1['hotel_id'] = $competitorHotelInstance;
                            $dA1['hotel_name'] = $competitorsData[0]->hotel_name;
                            $dA2[] = $dA1;
                            $dA1 = null;
                        }
                    }
                    if (isset($dA2)) {
                        $hotelPrice->competitorsData = array_filter($dA2);
                        $dA2 = null;
                    }
                }
                return CompetitorAvgPriceResource::collection($prices);
            }
            dd('Error: Data Not Found :  HRSHotelsCompetitorsAvgPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
