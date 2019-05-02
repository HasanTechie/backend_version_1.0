<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompetitorAvgPrice as CompetitorAvgPriceResource;
use App\Http\Resources\CompetitorRoomPrice as CompetitorRoomPriceResource;
use App\Http\Resources\CompetitorRoomAvgPrice as CompetitorRoomAvgPriceResource;
use App\Http\Resources\Event as EventResource;
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

    public function HRSHotels($rows, $apiKey, $city)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('hotels_hrs')
                ->where('city', '=', $city);
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

    public function HRSHotelsCompetitorsAvgPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIdsArray = explode(',', str_replace(array('[', ']'), '', $competitorIds));

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
                foreach ($competitorIdsArray as $competitorHotelInstance) {
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
                        $dA2[] = $dA1;
                        $dA1 = null;
                    }

                }
                $hotel->competitorsData = array_filter($dA2);
                $dA2 = null;
            }

            return CompetitorAvgPriceResource::collection($prices);

        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSHotelsCompetitorsRoomsPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIdsArray = explode(',', str_replace(array('[', ']'), '', $competitorIds));

            $dates = DB::table('rooms_hrs')
                ->select(DB::raw('prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($rows > 0) ? $dates = $dates->limit($rows) : null;
            $dates = $dates->get();

            foreach ($dates as $date) {
                $mainHotelRooms = DB::table('rooms_hrs')
                    ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, rooms_hrs.room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                    ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotel],
                        ['check_in_date', '=', $date->check_in_date],
//                        ['request_date', '<=', date("Y-m-d")],
//                        ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                    ])->get();
            }

            foreach ($mainHotelRooms as $mainHotelRoom) {
                foreach ($competitorIdsArray as $competitorId) {
                    $competitorsRooms = DB::table('rooms_hrs')
                        ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, criteria, rooms_hrs.room, prices_hrs.price, prices_hrs.request_date'))
                        ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                        ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                        ->where([
                            ['rooms_hrs.hotel_id', '=', $competitorId],
                            ['rooms_hrs.room', '=', $mainHotelRoom->room],
                            ['rooms_hrs.room_type', '=', $mainHotelRoom->room_type],
                            ['check_in_date', '=', $date->check_in_date],
//                            ['request_date', '<=', date("Y-m-d")],
//                            ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                        ])->get();

                    if (count($competitorsRooms) > 0) {
                        foreach ($competitorsRooms as $competitorsRoomsInstance) {
                            $dA1['price'] = $competitorsRoomsInstance->price;
                            $dA1['room'] = $competitorsRoomsInstance->room;
                            $dA1['room_criteria'] = $competitorsRoomsInstance->criteria;
                            $dA1['check_in_date'] = $date->check_in_date;
                            $dA1['request_date'] = $competitorsRoomsInstance->request_date;
                            $dA1['hotel_id'] = $competitorId;
                            $dA1['hotel_name'] = $competitorsRoomsInstance->hotel_name;
                            if (preg_replace('/[0-9]+/', '', str_replace(' ', '', $mainHotelRoom->criteria))
                                ==
                                preg_replace('/[0-9]+/', '', str_replace(' ', '', $competitorsRoomsInstance->criteria))) {
                                $dA2[] = $dA1;
                                $dA1 = null;
                            }
                        }
                    }

                }
                $mainHotelRoom->competitors = $dA2;
                $dA2 = null;
            }
            return CompetitorRoomPriceResource::collection($mainHotelRooms);

        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSHotelsCompetitorsRoomsAvgPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIdsArray = explode(',', str_replace(array('[', ']'), '', $competitorIds));

            $dates = DB::table('rooms_hrs')
                ->select(DB::raw('prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($rows > 0) ? $dates = $dates->limit($rows) : null;
            $dates = $dates->get();

            foreach ($dates as $date) {
                $mainHotelRooms = DB::table('rooms_hrs')
                    ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, rooms_hrs.room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                    ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotel],
                        ['check_in_date', '=', $date->check_in_date],
//                        ['request_date', '<=', "2019-04-25"],
//                        ['request_date', '<=', date("Y-m-d")],
//                        ['request_date', '>=', "2019-04-29"],
//                        ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                    ])->get();
            }

            foreach ($mainHotelRooms as $mainHotelRoom) {
                foreach ($competitorIdsArray as $competitorId) {
                    $competitorsRooms = DB::table('rooms_hrs')
                        ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, criteria, rooms_hrs.room, prices_hrs.price, prices_hrs.request_date'))
                        ->join('prices_hrs', 'prices_hrs.r_id', '=', 'rooms_hrs.id')
                        ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                        ->where([
                            ['rooms_hrs.hotel_id', '=', $competitorId],
                            ['rooms_hrs.room', '=', $mainHotelRoom->room],
                            ['rooms_hrs.room_type', '=', $mainHotelRoom->room_type],
                            ['check_in_date', '=', $date->check_in_date],
//                            ['request_date', '<=', date("Y-m-d")],
//                            ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                        ])->get();

                    if (count($competitorsRooms) > 0) {
                        foreach ($competitorsRooms as $competitorsRoomsInstance) {
                            $competitorPrice = $competitorsRoomsInstance->price;
                            if (preg_replace('/[0-9]+/', '', str_replace(' ', '', $mainHotelRoom->criteria))
                                ==
                                preg_replace('/[0-9]+/', '', str_replace(' ', '', $competitorsRoomsInstance->criteria))) {
                                $dA1[] = $competitorPrice;
                            }
                        }
                    }

                }
                $competitorPriceAverage = round(array_sum($dA1) / count($dA1), 2);
                $mainHotelRoom->competitors_rooms_avg_price = $competitorPriceAverage;
                $dA2 = null;
            }
            return CompetitorRoomAvgPriceResource::collection($mainHotelRooms);

        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function Events($rows, $apiKey, $city)
    {
        if ($apiKey == $this->apiKey) {
            $events = DB::table('events')
                ->where('city', '=', $city);
            ($rows > 0) ? $events = $events->limit($rows) : null;
            $events = $events->get();

            return EventResource::collection($events);

        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
