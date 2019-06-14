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

    public function HRSHotelsCompetitorsAvgPrices($rows, $apiKey, $userid, $dateFrom, $dateTo, $room)
    {
        if ($apiKey == $this->apiKey) {

            $competitorIds = DB::table('competitors')->select('hotel_id')->where('user_id', '=', $userid)->get();
            $competitorIdsArray = [];
            foreach ($competitorIds as $competitorIdInstance1) {
                $competitorIdsArray[] = $competitorIdInstance1->hotel_id;
            }

            $hotelId = DB::table('users')->select('hotel_id')->where('id', '=', $userid)->get();

            $hotelId = $hotelId[0]->hotel_id;

            $prices = DB::table('rooms_hrs')
                ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotelId],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($room != 'All') ? $prices = $prices->where('room', '=', $room) : null;
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
                            ]);
                        ($room != 'All') ? $competitorsData = $competitorsData->where('room', '=', $room) : null;
                        $competitorsData = $competitorsData->groupBy('check_in_date')->get();

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

    public function HRSHotelsCompetitorsPricesApex($rows, $apiKey, $userid, $dateFrom, $dateTo, $room)
    {


        if ($apiKey == $this->apiKey) {

            $competitorIds = DB::table('competitors')->select('hotel_id')->where('user_id', '=', $userid)->get();
            $competitorIdsArray = [];
            foreach ($competitorIds as $competitorIdInstance1) {
                $competitorIdsArray[] = $competitorIdInstance1->hotel_id;
            }

            $hotelId = DB::table('users')->select('hotel_id')->where('id', '=', $userid)->get();

            $hotelId = $hotelId[0]->hotel_id;

            if (!in_array($hotelId, $competitorIdsArray)) {
                array_unshift($competitorIdsArray, $hotelId);
            }

            /*
            if (empty($dateFrom)) {
                $dateFrom = '2019-01-01';
            }
            if (empty($dateTo)) {
                $dateTo = '2021-01-01';
            }
            */
            $prices = DB::table('rooms_hrs')
                ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotelId],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($room != 'All') ? $prices = $prices->where('room', '=', $room) : null;
            ($rows > 0) ? $prices = $prices->limit($rows) : null;
            $prices = $prices->get();

            if (isset($prices)) {

                foreach ($prices as $priceInstance) {
                    foreach ($competitorIdsArray as $competitorHotelInstance) {
                        $competitorsData = DB::table('rooms_hrs')
                            ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                            ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                            ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                            ->where([
                                ['rooms_hrs.hotel_id', '=', $competitorHotelInstance],
                                ['check_in_date', '=', $priceInstance->check_in_date],
                            ]);
                        ($room != 'All') ? $competitorsData = $competitorsData->where('room', '=', $room) : null;
                        $competitorsData = $competitorsData->groupBy('check_in_date')->get();

                        if (count($competitorsData) > 0) {
                            $dA1['price'] = (!empty($competitorsData[0]->price) ? $competitorsData[0]->price : 'null');
//                            $dA1['check_in_date'] = $hotel->check_in_date;
//                            $dA1['hotel_id'] = $competitorHotelInstance;
                            $dA1['hotel_name'] = $competitorsData[0]->hotel_name;
                            $dA2[$dA1['hotel_name']][] = (!empty($dA1['price']) ? $dA1['price'] : 'null');
                            $dA1 = null;
                        }
                    }
                    $firstArrayLength = '';
                    $i = 0;
                    if (isset($dA2)) {

                        foreach (array_keys($dA2) as $index => $key) {
                            if ($i == 0) {
                                $firstArrayLength = count($dA2[$key]);
                                $i++;
                            }
                            if ($firstArrayLength != count($dA2[$key])) {
                                array_push($dA2[$key], null);
                            }
                        }
                    }
                }
                $rooms = DB::table('rooms_hrs')->select('room')->distinct()->where('hotel_id', '=', $hotelId)->get();
                $roomsArray = ['All'];
                foreach ($rooms as $roomInstance) {
                    $roomsArray[] = $roomInstance->room;
                }
                $check_in_datesArray = [];
                foreach ($prices as $price) {
                    $check_in_datesArray[] = $price->check_in_date;
                }
                $competitorsDataArray = [];
                if (isset($dA2)) {
                    foreach ($dA2 as $key => $value) {
                        $dA3['name'] = $key;
                        $dA3['data'] = $value;
                        $competitorsDataArray[] = $dA3;
                    }
                }
                $object = (object)array(
                    'rooms' => $roomsArray,
                    'xAxis' => $check_in_datesArray,
                    'yAxis' => $competitorsDataArray
                );
                return CompetitorPriceResourceApex::make($object);
            }
            dd('Error: Data Not Found :  HRSHotelsCompetitorsAvgPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
