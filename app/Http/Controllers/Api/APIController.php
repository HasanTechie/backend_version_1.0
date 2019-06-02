<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\CompetitorAvgPrice as CompetitorAvgPriceResource;
use App\Http\Resources\CompetitorPriceApex as CompetitorPriceResourceApex;
use App\Http\Resources\CompetitorRoomPrice as CompetitorRoomPriceResource;
use App\Http\Resources\CompetitorRoomAvgPrice as CompetitorRoomAvgPriceResource;
use App\Http\Resources\Event as EventResource;
use App\Http\Resources\Hotel as HotelResource;
use App\Http\Resources\RoomPrice as RoomPriceResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class APIController extends Controller
{
    //
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW';
    }

    public function HRSHotels($rows, $apiKey, $city)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('hotels_hrs');
            if ($city == 'All') {
                $hotels = $hotels->whereIn('city', ['Rome', 'Berlin']);
            } else {
                $hotels = $hotels->where('city', '=', $city);
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

    public function HRSHotelsCompetitorsAvgPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
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

    public function HRSHotelsCompetitorsPricesApex($rows, $apiKey, $userid, $dateFrom, $dateTo, $room)
    {


        if ($apiKey == $this->apiKey) {

            $competitorIds = DB::table('competitors')->select('hotel_id')->where('user_id', '=', $userid)->get();
            $competitorIdsArray = [];
            foreach ($competitorIds as $competitorIdInstance1) {
                $competitorIdsArray[] = $competitorIdInstance1->hotel_id;
            }

            $hotelId = DB::table('users')->select('hotel_id')->where('id', '=', $userid)->get();

            if (!in_array($hotelId[0]->hotel_id, $competitorIdsArray)) {
                array_unshift($competitorIdsArray, $hotelId[0]->hotel_id);
            }

            if ($room == 'All') {
                $returnAllRooms = true;
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
                    ['rooms_hrs.hotel_id', '=', $hotelId[0]->hotel_id],
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
                $rooms = DB::table('rooms_hrs')->select('room')->distinct()->where('hotel_id', '=', $hotelId[0]->hotel_id)->get();
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

    public function HRSHotelsCompetitorsRoomsPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIdsArray = explode(',', $competitorIds);
            $dates = DB::table('rooms_hrs')
                ->select(DB::raw('prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($rows > 0) ? $dates = $dates->limit($rows) : null;
            $dates = $dates->get();

            foreach ($dates as $dateInstance) {
                $mainHotelRooms = DB::table('rooms_hrs')
                    ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, rooms_hrs.id as room_id, rooms_hrs.room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                    ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotel],
                        ['check_in_date', '=', $dateInstance->check_in_date],
//                        ['request_date', '<=', date("Y-m-d")],
//                        ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                    ])->groupBy('room', 'criteria', 'room_type')->get();
                if (isset($mainHotelRooms)) {
                    foreach ($mainHotelRooms as $mainHotelRoom) {
                        foreach ($competitorIdsArray as $competitorId) {
                            $competitorsRooms = DB::table('rooms_hrs')
                                ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, criteria, rooms_hrs.room, prices_hrs.price, prices_hrs.request_date'))
                                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                                ->where([
                                    ['rooms_hrs.hotel_id', '=', $competitorId],
                                    ['rooms_hrs.room', '=', $mainHotelRoom->room],
                                    ['rooms_hrs.room_type', '=', $mainHotelRoom->room_type],
                                    ['check_in_date', '=', $dateInstance->check_in_date],
//                            ['request_date', '<=', date("Y-m-d")],
//                            ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                                ])->groupBy('room', 'criteria', 'room_type')->get();
                            if (count($competitorsRooms) > 0) {
                                foreach ($competitorsRooms as $competitorsRoomsInstance) {
                                    $dA1['price'] = round($competitorsRoomsInstance->price, 2);
                                    $dA1['room'] = $competitorsRoomsInstance->room;
                                    $dA1['room_criteria'] = $competitorsRoomsInstance->criteria;
                                    $dA1['check_in_date'] = $dateInstance->check_in_date;
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
                        $dateInstance->hotel_name = $mainHotelRoom->hotel_name;
                        $dateInstance->hotel_id = $mainHotelRoom->hotel_id;
                        $dateInstance->room_id = $mainHotelRoom->room_id;
                        $dateInstance->room = $mainHotelRoom->room;
                        $dateInstance->price = $mainHotelRoom->price;
                        $dateInstance->criteria = $mainHotelRoom->criteria;
                        $dateInstance->room_type = $mainHotelRoom->room_type;
                        $dateInstance->check_in_date = $mainHotelRoom->check_in_date;
                        $dateInstance->request_date = $mainHotelRoom->request_date;
                        if (isset($dA2)) {
                            $dateInstance->competitors = $dA2;
                            $dA2 = null;
                        }
                    }
                }
            }
            return CompetitorRoomPriceResource::collection($dates);
//            dd('Error: Data Not Found : HRSHotelsCompetitorsRoomsPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSHotelsCompetitorsRoomsAvgPrices($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIdsArray = explode(',', $competitorIds);
            $dates = DB::table('rooms_hrs')
                ->select(DB::raw('prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($rows > 0) ? $dates = $dates->limit($rows) : null;
            $dates = $dates->get();

            foreach ($dates as $dateInstance) {
                $mainHotelRooms = DB::table('rooms_hrs')
                    ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, rooms_hrs.id as room_id, rooms_hrs.room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                    ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotel],
                        ['check_in_date', '=', $dateInstance->check_in_date],
//                        ['request_date', '<=', date("Y-m-d")],
//                        ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                    ])->groupBy('room', 'criteria', 'room_type')->get();
                if (isset($mainHotelRooms)) {
                    foreach ($mainHotelRooms as $mainHotelRoom) {
                        foreach ($competitorIdsArray as $competitorId) {
                            $competitorsRooms = DB::table('rooms_hrs')
                                ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, criteria, rooms_hrs.room, prices_hrs.price, prices_hrs.request_date'))
                                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                                ->where([
                                    ['rooms_hrs.hotel_id', '=', $competitorId],
                                    ['rooms_hrs.room', '=', $mainHotelRoom->room],
                                    ['rooms_hrs.room_type', '=', $mainHotelRoom->room_type],
                                    ['check_in_date', '=', $dateInstance->check_in_date],
//                            ['request_date', '<=', date("Y-m-d")],
//                            ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                                ])->groupBy('room', 'criteria', 'room_type')->get();
                            if (count($competitorsRooms) > 0) {
                                foreach ($competitorsRooms as $competitorsRoomsInstance) {
                                    if (preg_replace('/[0-9]+/', '', str_replace(' ', '', $mainHotelRoom->criteria))
                                        ==
                                        preg_replace('/[0-9]+/', '', str_replace(' ', '', $competitorsRoomsInstance->criteria))) {
                                        $dA2[] = round($competitorsRoomsInstance->price, 2);
                                    }
                                }
                            }
                        }
                        $dateInstance->hotel_name = $mainHotelRoom->hotel_name;
                        $dateInstance->hotel_id = $mainHotelRoom->hotel_id;
                        $dateInstance->room_id = $mainHotelRoom->room_id;
                        $dateInstance->room = $mainHotelRoom->room;
                        $dateInstance->price = $mainHotelRoom->price;
                        $dateInstance->criteria = $mainHotelRoom->criteria;
                        $dateInstance->room_type = $mainHotelRoom->room_type;
                        $dateInstance->check_in_date = $mainHotelRoom->check_in_date;
                        $dateInstance->request_date = $mainHotelRoom->request_date;
                        if (isset($dA2)) {
                            if (is_array($dA2) && (count($dA2) > 0)) {
                                $competitorsRoomsAvgPrice = round(array_sum($dA2) / count($dA2), 2);
                            }
                            $dateInstance->competitors_rooms_avg_price = $competitorsRoomsAvgPrice;
                            $dA2 = null;
                        }
                    }
                }
            }
            return CompetitorRoomAvgPriceResource::collection($dates);

        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function HRSHotelsCompetitorsRoomsPricesNew($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIdsArray = explode(',', $competitorIds);
            $dates = DB::table('rooms_hrs')
                ->select(DB::raw('prices_hrs.check_in_date'))
                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                ->where([
                    ['rooms_hrs.hotel_id', '=', $hotel],
                    ['check_in_date', '>=', $dateFrom],
                    ['check_in_date', '<=', $dateTo],
                ])->groupBy('check_in_date');
            ($rows > 0) ? $dates = $dates->limit($rows) : null;
            $dates = $dates->get();

            foreach ($dates as $dateInstance) {
                $mainHotelRooms = DB::table('rooms_hrs')
                    ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, rooms_hrs.id as room_id, rooms_hrs.room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                    ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotel],
                        ['check_in_date', '=', $dateInstance->check_in_date],
//                        ['request_date', '<=', date("Y-m-d")],
//                        ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                    ])->groupBy('room', 'criteria', 'room_type')->get();
                if (isset($mainHotelRooms)) {
                    foreach ($mainHotelRooms as $mainHotelRoom) {
                        foreach ($competitorIdsArray as $competitorId) {
                            $competitorsRooms = DB::table('rooms_hrs')
                                ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, criteria, rooms_hrs.room, prices_hrs.price, prices_hrs.request_date'))
                                ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                                ->where([
                                    ['rooms_hrs.hotel_id', '=', $competitorId],
                                    ['rooms_hrs.room', '=', $mainHotelRoom->room],
                                    ['rooms_hrs.room_type', '=', $mainHotelRoom->room_type],
                                    ['check_in_date', '=', $dateInstance->check_in_date],
//                            ['request_date', '<=', date("Y-m-d")],
//                            ['request_date', '>=', date("Y-m-d", strtotime("-5 day"))],
                                ])->groupBy('room', 'criteria', 'room_type')->get();
                            if (count($competitorsRooms) > 0) {
                                foreach ($competitorsRooms as $competitorsRoomsInstance) {
                                    $dA1['price'] = round($competitorsRoomsInstance->price, 2);
                                    $dA1['room'] = $competitorsRoomsInstance->room;
                                    $dA1['room_criteria'] = $competitorsRoomsInstance->criteria;
                                    $dA1['check_in_date'] = $dateInstance->check_in_date;
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
                        $dateInstance->hotel_name = $mainHotelRoom->hotel_name;
                        $dateInstance->hotel_id = $mainHotelRoom->hotel_id;
                        $dateInstance->room_id = $mainHotelRoom->room_id;
                        $dateInstance->room = $mainHotelRoom->room;
                        $dateInstance->price = $mainHotelRoom->price;
                        $dateInstance->criteria = $mainHotelRoom->criteria;
                        $dateInstance->room_type = $mainHotelRoom->room_type;
                        $dateInstance->check_in_date = $mainHotelRoom->check_in_date;
                        $dateInstance->request_date = $mainHotelRoom->request_date;
                        if (isset($dA2)) {
                            $dateInstance->competitors = $dA2;
                            $dA2 = null;
                        }
                    }
                }
            }
            return CompetitorRoomPriceResource::collection($dates);
//            dd('Error: Data Not Found : HRSHotelsCompetitorsRoomsPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    

    public function Events($rows, $apiKey, $city)
    {
        if ($apiKey == $this->apiKey) {
            $events = DB::table('events')
                ->where('city', '=', $city);
            if (isset($events)) {
                ($rows > 0) ? $events = $events->limit($rows) : null;
                $events = $events->get();
                return EventResource::collection($events);
            }
            dd('Error: Data Not Found : Events');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
