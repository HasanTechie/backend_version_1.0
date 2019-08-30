<?php
namespace App\Http\Controllers\Api;
use App\Http\Resources\CompetitorPriceApex as CompetitorPriceResourceApex;
use App\Http\Resources\CompetitorAllRoomPrice as CompetitorAllRoomPriceResource;
use App\Http\Resources\CompetitorRoomPrice as CompetitorRoomPriceResource;
use App\Http\Resources\CompetitorRoomAvgPrice as CompetitorRoomAvgPriceResource;
use App\Http\Resources\CompetitorAvgPrice as CompetitorAvgPriceResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CompetitorsPriceAPIController extends Controller
{
    //
    protected $apiKey;
    public function __construct()
    {
        $this->apiKey = 'KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW';
    }
    public function HRSHotelsCompetitorsPricesApex($rows, $apiKey, $userid, $dateFrom, $dateTo, $room)
    {
        if ($apiKey == $this->apiKey) {
            $hotelId = DB::table('users')->select('hotel_id', 'hotels_hrs.name')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'users.hotel_id')
                ->where('users.id', '=', $userid)->get();
            $competitorIds = DB::table('competitors')->select('hotel_id', 'hotels_hrs.name')
                ->join('hotels_hrs', 'hotels_hrs.id', '=', 'competitors.hotel_id')
                ->where('user_id', '=', $userid)->get();
            $competitorIdsArray = [];
            if (count($hotelId)) {
                $tempArray['id'] = $hotelId[0]->hotel_id;
                $tempArray['name'] = $hotelId[0]->name;
                $competitorIdsArray[] = $tempArray;
                foreach ($competitorIds as $competitorIdInstance1) {
                    $tempArray['id'] = $competitorIdInstance1->hotel_id;
                    $tempArray['name'] = $competitorIdInstance1->name;
                    $competitorIdsArray[] = $tempArray;
                }
                $check_in_datesArray = [];
                $date = ($dateFrom < date("Y-m-d")) ? date("Y-m-d") : $dateFrom;
                $endDate = ($dateTo > date("Y-m-d", strtotime("+365 day"))) ? date("Y-m-d", strtotime("+365 day")) : $dateTo;;
                $dA7 = [];
                $dAm1 = [];
                $dAm5[] = ['text' => 'Dates', 'align' => 'left', 'sortable' => false, 'value' => 'date'];
                foreach ($competitorIdsArray as $competitorHotelInstance) {
                    $l = 0;
                    $competitorsDataForMonthlyAverage = DB::table('rooms_hrs')
                        ->select(DB::raw("DATE_FORMAT(prices_hrs.check_in_date, '%Y-%m') AS month, DATE_FORMAT(prices_hrs.check_in_date, '%M, %Y') AS yearMonth,
                            ROUND(AVG(prices_hrs.price),2) AS price,hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id"))
                        ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                        ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                        ->where([
                            ['rooms_hrs.hotel_id', '=', $competitorHotelInstance['id']],
                        ]);
                    (strtolower($room) != 'all') ? $competitorsDataForMonthlyAverage = $competitorsDataForMonthlyAverage->where('room', '=', $room) : null;
                    $competitorsDataForMonthlyAverage = $competitorsDataForMonthlyAverage->groupBy(['yearMonth', 'month'])->orderBy('month')->get();
                    if (count($competitorsDataForMonthlyAverage) > 0) {
                        foreach ($competitorsDataForMonthlyAverage as $competitorsDataForMonthlyAverageInstance) {
                            $dAm1[$competitorsDataForMonthlyAverageInstance->yearMonth][$competitorsDataForMonthlyAverageInstance->hotel_id] = (isset($competitorsDataForMonthlyAverageInstance->price) ? $competitorsDataForMonthlyAverageInstance->price : null);
                            if ($l == 0) {
                                $dAm4['text'] = $competitorsDataForMonthlyAverageInstance->hotel_name;
                                $dAm4['value'] = $competitorsDataForMonthlyAverageInstance->hotel_id;
                                $dAm5[] = $dAm4;
                                $l++;
                            }
                        }
                    }
                }
                $dAm3 = [];
                foreach ($dAm1 as $dAm1Key => $dAm1Instance) {
                    $dAm2['date'] = $dAm1Key;
                    foreach ($dAm1Instance as $dAm1InstanceKey => $dAm1InstanceKaInstance) {
                        $dAm2[$dAm1InstanceKey] = $dAm1InstanceKaInstance;
                    }
                    $dAm3[] = $dAm2;
                }
                while (strtotime($date) <= strtotime($endDate)) {
                    foreach ($competitorIdsArray as $competitorHotelInstance) {
                        $competitorsData = DB::table('rooms_hrs')
                            ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                            ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                            ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                            ->where([
                                ['rooms_hrs.hotel_id', '=', $competitorHotelInstance['id']],
                                ['check_in_date', '=', $date],
                            ]);
                        (strtolower($room) != 'all') ? $competitorsData = $competitorsData->where('room', '=', $room) : null;
                        $competitorsData = $competitorsData->groupBy('check_in_date')->get();
                        if (count($competitorsData) > 0) {
                            $dA1['price'] = (!empty($competitorsData[0]->price) ? $competitorsData[0]->price : null);
                            $dA1['hotel_name'] = $competitorHotelInstance['name'];
                            $dA2[$dA1['hotel_name']][] = $dA2A[] = (!empty($dA1['price']) ? $dA1['price'] : null);
                            $dA1 = null;
                        } else {
                            $dA1['price'] = null;
                            $dA1['hotel_name'] = $competitorHotelInstance['name'];
                            $dA2[$dA1['hotel_name']][] = $dA2A[] = (!empty($dA1['price']) ? $dA1['price'] : null);
                            $dA1 = null;
                        }
                    }
                    $firstArrayLength = 0;
                    $i = 0;
                    if (isset($dA2)) {
                        foreach (array_keys($dA2) as $index => $key) { // maybe useless code; code 100% sure.
                            if ($i == 0) {
                                $firstArrayLength = count($dA2[$key]);
                                $i++;
                            }
                            if ($firstArrayLength != count($dA2[$key])) {
                                array_push($dA2[$key], null);
                            }
                        }
                        $dA6['date'] = $date;
                        $k = 1;
                        foreach ($dA2A as $dA2AKaInstance) {
                            $pValue = 'p' . $k++;
                            if (!empty($dA2AKaInstance)) {
                                $dA6[$pValue] = $dA2AKaInstance;
                            } else {
                                if (isset($dA8)) {
                                    $dA6[$pValue] = $dA8[$pValue];
                                } else {
                                    $dA6[$pValue] = 'not available';
                                }
                            }
                        }
                        $dA8 = $dA6;
                        $dA2A = [];
                        $dA7[] = $dA6;
                        $dA6 = [];
                    }
                    $check_in_datesArray[] = $date;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
                $rooms = DB::table('rooms_hrs')->select('room')->distinct()->where('hotel_id', '=', $hotelId[0]->hotel_id)->get();
                $roomsArray = ['All'];
                foreach ($rooms as $roomInstance) {
                    $roomsArray[] = $roomInstance->room;
                }
                $competitorsDataArray = [];
                if (isset($dA2)) {
                    foreach ($dA2 as $key => $dA2instance) {
                        $tempPrice = 0;
                        foreach ($dA2instance as $key2 => $dA2InstanceKaInstance) {
                            if (empty($dA2InstanceKaInstance)) {
                                $dA2[$key][$key2] = $tempPrice;
                            }
                            if (!empty($dA2InstanceKaInstance)) {
                                $tempPrice = $dA2InstanceKaInstance;
                            }
                        }
                    }
                    $j = 1;
                    $dA5[] = ['text' => 'Dates', 'align' => 'left', 'sortable' => false, 'value' => 'date'];
                    foreach ($dA2 as $key => $value) {
                        $a = array_filter($value);
                        if (count($a) > 0) {
                            $average = round(array_sum($a) / count($a), 2);
                        } else {
                            $average = 0;
                        }
                        foreach ($value as $key2 => $valueInstance) {
                            if ($valueInstance == 0) {
                                $value[$key2] = $average;
                            }
                        }
                        if (array_sum($value) != 0) {
                            $dA3['name'] = $dA4['text'] = $key;
                            $dA4['value'] = 'p' . $j++;
                            $dA5[] = $dA4;
                            $dA3['data'] = $value;
                            $competitorsDataArray[] = $dA3;
                        } else {
                            $dA3['name'] = $dA4['text'] = $key;
                            $dA4['value'] = 'p' . $j++;
                            $dA5[] = $dA4;
                        }
                    }
                }
                $object = (object)array(
                    'rooms' => $roomsArray,
                    'xAxis' => $check_in_datesArray,
                    'yAxis' => $competitorsDataArray,
                    'dataTableAllData' => [
                        'headers' => $dA5,
                        'tableData' => $dA7
                    ],
                    'dataTableMonthlyAverage' => [
                        'headers' => $dAm5,
                        'tableData' => $dAm3
                    ]
                );
                return CompetitorPriceResourceApex::make($object);
            }
            dd('Error: Data Not Found :  HRSHotelsCompetitorsPricesApex');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
    public function HRSHotelsCompetitorAllRoomsPrices($rows, $apiKey, $userid, $dateFrom, $dateTo) //i guess its not used but Wilhelm asked me develop it
    {
        if ($apiKey == $this->apiKey) {
            $competitorIds = DB::table('competitors')->select('hotel_id')->where('user_id', '=', $userid)->get();
            $competitorIdsArray = [];
            foreach ($competitorIds as $competitorIdInstance1) {
                $competitorIdsArray[] = $competitorIdInstance1->hotel_id;
            }
            $hotelId = DB::table('users')->select('hotel_id')->where('id', '=', $userid)->get();
            if (count($hotelId)) {
                $hotelId = $hotelId[0]->hotel_id;
                $dates = DB::table('rooms_hrs')
                    ->select(DB::raw('prices_hrs.check_in_date'))
                    ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotelId],
                        ['check_in_date', '>=', $dateFrom],
                        ['check_in_date', '<=', $dateTo],
                    ])->groupBy('check_in_date');
                ($rows > 0) ? $dates = $dates->limit($rows) : null;
                $dates = $dates->get();
                foreach ($dates as $date) {
                    $mainHotelRooms = DB::table('rooms_hrs')
                        ->select(DB::raw('hotels_hrs.name as hotel_name, price_should, hotels_hrs.id as hotel_id, rooms_hrs.id as room_id, rooms_hrs.room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                        ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                        ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                        ->where([
                            ['rooms_hrs.hotel_id', '=', $hotelId],
                            ['check_in_date', '=', $date->check_in_date],
                        ])->groupBy('room');
                    $mainHotelRooms = $mainHotelRooms->get();
                    $date->data[] = $mainHotelRooms;
                    foreach ($competitorIdsArray as $competitorId) {
                        $competitorsRooms = DB::table('rooms_hrs')
                            ->select(DB::raw('hotels_hrs.name as hotel_name, price_should, hotels_hrs.id as hotel_id, criteria, rooms_hrs.room, prices_hrs.price, prices_hrs.request_date'))
                            ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                            ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                            ->where([
                                ['rooms_hrs.hotel_id', '=', $competitorId],
                                ['check_in_date', '=', $date->check_in_date],
                            ])->groupBy('room');
                        $competitorsRooms = $competitorsRooms->get();
                        if (count($competitorsRooms) > 0) {
                            $date->data[] = $competitorsRooms;
                        }
                    }
                }
                return CompetitorAllRoomPriceResource::collection($dates);
            }
            dd('Error: Data Not Found :  HRSHotelsCompetitorAllRoomsPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
    public function HRSHotelsCompetitorsRoomsPrices($rows, $apiKey, $userid, $dateFrom, $dateTo, $room)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIds = DB::table('competitors')->select('hotel_id')->where('user_id', '=', $userid)->get();
            $competitorIdsArray = [];
            foreach ($competitorIds as $competitorIdInstance1) {
                $competitorIdsArray[] = $competitorIdInstance1->hotel_id;
            }
            $hotelId = DB::table('users')->select('hotel_id')->where('id', '=', $userid)->get();
            if (count($hotelId)) {
                $hotelId = $hotelId[0]->hotel_id;
                $prices = DB::table('rooms_hrs')
                    ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, price_should,  ROUND(avg(prices_hrs.price),2) as price, prices_hrs.check_in_date'))
                    ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotelId],
                        ['check_in_date', '>=', $dateFrom],
                        ['check_in_date', '<=', $dateTo],
                    ])->groupBy('check_in_date');
                (strtolower($room) != 'all') ? $prices = $prices->where('room', '=', $room) : null;
                ($rows > 0) ? $prices = $prices->limit($rows) : null;
                $prices = $prices->get();
                foreach ($prices as $priceInstance) {
                    $mainHotelRooms = DB::table('rooms_hrs')
                        ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, price_should, rooms_hrs.id as room_id, rooms_hrs.room as room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                        ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                        ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                        ->where([
                            ['rooms_hrs.hotel_id', '=', $hotelId],
                            ['check_in_date', '=', $priceInstance->check_in_date],
                        ])->groupBy('room', 'criteria', 'room_type');
                    (strtolower($room) != 'all') ? $mainHotelRooms = $mainHotelRooms->where('room', '=', $room) : null;
                    $mainHotelRooms = $mainHotelRooms->get();
                    if (isset($mainHotelRooms)) {
                        foreach ($mainHotelRooms as $mainHotelRoom) {
                            foreach ($competitorIdsArray as $competitorId) {
                                $competitorsRooms = DB::table('rooms_hrs')
                                    ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, price_should, criteria, rooms_hrs.id as room_id, rooms_hrs.room as room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                                    ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                                    ->where([
                                        ['rooms_hrs.hotel_id', '=', $competitorId],
                                        ['rooms_hrs.room', '=', $mainHotelRoom->room],
                                        ['rooms_hrs.room_type', '=', $mainHotelRoom->room_type],
                                        ['check_in_date', '=', $priceInstance->check_in_date],
                                    ])->groupBy('room', 'criteria', 'room_type');
                                (strtolower($room) != 'all') ? $competitorsRooms = $competitorsRooms->where('room', '=', $room) : null;
                                $competitorsRooms = $competitorsRooms->get();
                                if (count($competitorsRooms) > 0) {
                                    foreach ($competitorsRooms as $competitorsRoomsInstance) {
                                        $dA1['price'] = round($competitorsRoomsInstance->price, 2);
                                        $dA1['price_should'] = round($competitorsRoomsInstance->price_should, 2);
                                        $dA1['room'] = $competitorsRoomsInstance->room;
                                        $dA1['room_criteria'] = $competitorsRoomsInstance->criteria;
                                        $dA1['check_in_date'] = $priceInstance->check_in_date;
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
                            $priceInstance->hotel_name = $mainHotelRoom->hotel_name;
                            $priceInstance->hotel_id = $mainHotelRoom->hotel_id;
                            $priceInstance->room_id = $mainHotelRoom->room_id;
                            $priceInstance->room = $mainHotelRoom->room;
                            $priceInstance->price_should = $mainHotelRoom->price_should;
                            $priceInstance->price = $mainHotelRoom->price;
                            $priceInstance->criteria = $mainHotelRoom->criteria;
                            $priceInstance->room_type = $mainHotelRoom->room_type;
                            $priceInstance->check_in_date = $mainHotelRoom->check_in_date;
                            $priceInstance->request_date = $mainHotelRoom->request_date;
                            if (isset($dA2)) {
                                $priceInstance->competitors = $dA2;
                                $dA2 = null;
                            }
                        }
                    }
                }
                return CompetitorRoomPriceResource::collection($prices);
            }
            dd('Error: Data Not Found : HRSHotelsCompetitorsRoomsPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
    public function HRSHotelsCompetitorsRoomsAvgPrices($rows, $apiKey, $userid, $dateFrom, $dateTo, $room)
    {
        if ($apiKey == $this->apiKey) {
            $competitorIds = DB::table('competitors')->select('hotel_id')->where('user_id', '=', $userid)->get();
            $competitorIdsArray = [];
            foreach ($competitorIds as $competitorIdInstance1) {
                $competitorIdsArray[] = $competitorIdInstance1->hotel_id;
            }
            $hotelId = DB::table('users')->select('hotel_id')->where('id', '=', $userid)->get();
            if (count($hotelId)) {
                $hotelId = $hotelId[0]->hotel_id;
                $dates = DB::table('rooms_hrs')
                    ->select(DB::raw('prices_hrs.check_in_date'))
                    ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                    ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                    ->where([
                        ['rooms_hrs.hotel_id', '=', $hotelId],
                        ['check_in_date', '>=', $dateFrom],
                        ['check_in_date', '<=', $dateTo],
                    ])->groupBy('check_in_date');
                (strtolower($room) != 'all') ? $dates = $dates->where('room', '=', $room) : null;
                ($rows > 0) ? $dates = $dates->limit($rows) : null;
                $dates = $dates->get();
                foreach ($dates as $dateInstance) {
                    $mainHotelRooms = DB::table('rooms_hrs')
                        ->select(DB::raw('hotels_hrs.name as hotel_name, hotels_hrs.id as hotel_id, rooms_hrs.id as room_id, rooms_hrs.room, prices_hrs.price, criteria, room_type, check_in_date, prices_hrs.request_date'))
                        ->join('prices_hrs', 'prices_hrs.room_id', '=', 'rooms_hrs.id')
                        ->join('hotels_hrs', 'hotels_hrs.id', '=', 'rooms_hrs.hotel_id')
                        ->where([
                            ['rooms_hrs.hotel_id', '=', $hotelId],
                            ['check_in_date', '=', $dateInstance->check_in_date],
                        ])->groupBy('room', 'criteria', 'room_type');
                    (strtolower($room) != 'all') ? $mainHotelRooms = $mainHotelRooms->where('room', '=', $room) : null;
                    $mainHotelRooms = $mainHotelRooms->get();
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
                                    ])->groupBy('room', 'criteria', 'room_type');
                                (strtolower($room) != 'all') ? $competitorsRooms = $competitorsRooms->where('room', '=', $room) : null;
                                $competitorsRooms = $competitorsRooms->get();
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
                                $dateInstance->competitors_rooms_avg_price = (isset($competitorsRoomsAvgPrice) ? $competitorsRoomsAvgPrice : null);
                                $dA2 = null;
                            }
                        }
                    }
                }
                return CompetitorRoomAvgPriceResource::collection($dates);
            }
            dd('Error: Data Not Found : HRSHotelsCompetitorsRoomsAvgPrices');
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
            if (count($hotelId)) {
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
                (strtolower($room) != 'all') ? $prices = $prices->where('room', '=', $room) : null;
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
                            (strtolower($room) != 'all') ? $competitorsData = $competitorsData->where('room', '=', $room) : null;
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
            }
            dd('Error: Data Not Found :  HRSHotelsCompetitorsAvgPrices');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
    public function HRSHotelsCompetitorsRoomsPricesOld($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
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
//            dd('Error: Data Not Found : HRSHotelsCompetitorsRoomsPricesOld');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
    public function HRSHotelsCompetitorsRoomsAvgPricesOld($rows, $apiKey, $hotel, $dateFrom, $dateTo, $competitorIds)
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
//            dd('Error: Data Not Found : HRSHotelsCompetitorsRoomsAvgPricesOld');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
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
            dd('Error: Data Not Found :  HRSHotelsCompetitorsAvgPricesOld');
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
