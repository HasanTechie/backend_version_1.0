<?php

use Goutte\Client as GoutteClient;

//use JonnyW\PhantomJs\Client as PhantomClient;
//use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Database\Seeder;

class GatheringHotels_hrsdotcom_ScrapingDataSeederMain extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function mainRun($dataArray)
    {
        session_start();

        global $city, $country, $checkInDate, $checkOutDate, $cityId;

        $city = $dataArray['city'];
        $country = $dataArray['country'];
        $cityId = $dataArray['city_id'];

//        $date = '2019-02-20';
        $date = $dataArray['start_date'];
        $end_date = $dataArray['end_date']; //last checkin date hogi last me
        //



        $client = new GoutteClient();

        while (strtotime($date) <= strtotime($end_date)) {


            $checkInDate = $date;

            $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));

            for ($i = 1; $i < 10000; $i++) {

                $url = "https://www.hrs.com/en/hotel/$city/d-$cityId/$i#container=&locationId=$cityId&requestUrl=%2Fen%2Fhotel%2F$city%2Fd-$cityId&showAlternates=false&toggle=&arrival=2019-02-28&departure=2019-03-01&lang=en&minPrice=false&roomType=double&singleRoomCount=0&doubleRoomCount=1&_=1550832580038";
                $crawler = $client->request('GET', $url);
                echo $url . "\n";
                $response = $client->getResponse();
                if ($response->getStatus() == 200) {


                    $crawler->filter('a.sw-hotel-list__link')->each(function ($node) {
                        $tempData = $node->attr('data-gtm-click');

//                        $da['hotel_id'] = preg_replace('/[^0-9]/', '', $da['link']);

                        $da['hotel_id'] = json_decode($tempData)->ecommerce->click->products[0]->id;

                        $adult = 1;
                        $url1 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $da['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=EUR&startDateDay=22&startDateMonth=02&startDateYear=2019&endDateDay=23&endDateMonth=02&endDateYear=2019&adults=$adult&singleRooms=1&doubleRooms=0&children=0#priceAnchor";
                        $adults = 2;
                        $url2 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $da['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=EUR&startDateDay=22&startDateMonth=02&startDateYear=2019&endDateDay=23&endDateMonth=02&endDateYear=2019&adults=$adults&singleRooms=0&doubleRooms=1&children=0#priceAnchor";

                        $client = new GoutteClient();
                        $crawler = $client->request('GET', $url1);
                        $crawler2 = $client->request('GET', $url2);

                        $da['all_rooms'][] = $crawler2->filter('table#basket > tbody > tr ')->each(function ($node) {
                            $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                            $dr['room_type'] = ($node->count() > 0) ? $node->attr('data-roomtype') : null;
                            $dr['details'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
                            $dr['price'] = ($node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->last()->text() : null;
                            $dr['included'] = ($node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->last()->text() : null;
                            foreach ($dr as $key => $value) {
                                if (!is_array($value)) {
                                    $dr[$key] = trim(str_replace(array("\r", "\n", "\t"), '', $value));
                                }
                                if (empty($value)) {
                                    unset($dr[$key]);
                                }
                            }
                            return $dr;
                        });

                        $da['all_rooms'][] = $crawler->filter('table#basket > tbody > tr ')->each(function ($node) {
                            $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                            $dr['room_type'] = ($node->count() > 0) ? $node->attr('data-roomtype') : null;
                            $dr['details'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
                            $dr['price'] = ($node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->last()->text() : null;
                            $dr['criteria'] = ($node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->last()->text() : null;
                            foreach ($dr as $key => $value) {
                                if (!is_array($value)) {
                                    $dr[$key] = trim(str_replace(array("\r", "\n", "\t"), '', $value));
                                }
                                if (empty($value)) {
                                    unset($dr[$key]);
                                }
                            }
                            return $dr;
                        });

                        $da['hotel_name'] = ($crawler->filter('div#detailsHead > h2 > span.title')->count() > 0) ? $crawler->filter('div#detailsHead > h2 > span.title')->text() : null;
                        $da['hotel_address'] = ($crawler->filter('address.hotelAdress')->count() > 0) ? $crawler->filter('address.hotelAdress')->text() : null;


                        global $dataArray;

                        $hid = 'hotel' . $da['hotel_name'] . 'address' . $da['hotel_address'];
                        $dh['hid'] = str_replace(' ', '', $hid);
                        if (DB::table('hotels_hrs')->where('hid', '=', $dh['hid'])->doesntExist()) {
                            $dh['hotel_uid'] = uniqid();
                            DB::table('hotels_hrs')->insert([
                                'uid' => $dh['hotel_uid'],
                                's_no' => 1,
                                'name' => $dh['hotel_name'],
                                'address' => $dh['hotel_address'],
                                'city' => $dataArray['city'],
                                'city_id_on_hrs' => $dataArray['city_id'],
                                'country' => $dataArray['country'],
                                'hid' => $dh['hid'],
                                'source' => $da['source'],
                                'created_at' => DB::raw('now()'),
                                'updated_at' => DB::raw('now()')
                            ]);
                            echo Carbon\Carbon::now()->toDateTimeString() . ' Completed hotel-> ' . $dh['hotel_name'] . "\n";
                        } else {
                            $resultHid = DB::table('hotels_hrs')->select('uid')->where('hid', '=', $dh['hid'])->get();
                            $dh['hotel_uid'] = $resultHid[0]->uid;
                            echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $dh['hotel_name'] . "\n";
                        }
//
//                        foreach ($da['all_single_rooms'] as $room) {
//
//                            if (isset($room['room']) || isset($room['price'])) {
//
//                                $rid = 'currentdate' . $requestDate . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelname' . trim(str_replace(' ', '', $dh['hotel_name'])) . 'room' . trim(str_replace(' ', '', $room['room'])) . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults
//
//                                if (DB::table('rooms_prices_eurobookings')->where('rid', '=', $rid)->doesntExist()) {
//
//                                    DB::table('rooms_prices_eurobookings')->insert([
//                                        'uid' => uniqid(),
//                                        's_no' => 1,
//                                        'price' => $room['price'],
//                                        'currency' => $dr['currency'],
//                                        'room' => $room['room'],
//                                        'short_description' => $room['details'],
//                                        'facilities' => serialize($room['room_facilities']),
//                                        'photo' => $room['img'],
//                                        'hotel_uid' => $dh['hotel_uid'],
//                                        'hotel_name' => $dh['hotel_name'],
//                                        'number_of_adults_in_room_request' => $dr['number_of_adults_in_room_request'],
//                                        'check_in_date' => $dr['check_in_date'],
//                                        'check_out_date' => $dr['check_out_date'],
//                                        'rid' => $rid,
//                                        'request_date' => $dr['request_date'],
//                                        'source' => $da['source'],
//                                        'created_at' => DB::raw('now()'),
//                                        'updated_at' => DB::raw('now()')
//                                    ]);
//                                    echo Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $dh['hotel_name'] . "\n";
//                                } else {
//                                    echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $dh['hotel_name'] . "\n";
//                                }
//                            }
//                        }
//
//
                    });
                }
                if ($response->getStatus() == 404) {
                    break;
                }
            }


            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
    }
}