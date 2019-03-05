<?php

use Goutte\Client as GoutteClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Database\Seeder;

class GatheringHotels_hrsdotcom_ScrapingDataSeederMain extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dataArray = [];

    public function mainRun($data)
    {
        $this->dataArray = $data;
//        if (session_status() == PHP_SESSION_NONE) {
//            session_start();
//        }

//        global $city, $country, $checkInDate, $checkOutDate, $cityId, $currency;
//
//        $city = $dataArray['city'];
//        $currency = $dataArray['currency'];
//        $country = $dataArray['country'];
//        $cityId = $dataArray['city_id'];
//
////        $date = '2019-02-20';
//        $date = $dataArray['start_date'];
//        $end_date = $dataArray['end_date']; //last checkin date hogi last me
        //


        $client = new GoutteClient();

        while (strtotime($this->dataArray['start_date']) <= strtotime($this->dataArray['end_date'])) {

            $this->dataArray['request_date'] = date("Y-m-d");
            $this->dataArray['check_in_date'] = $this->dataArray['start_date'];

            $this->dataArray['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($this->dataArray['start_date'])));

            for ($i = 1; $i < 10000; $i++) {

                try {

                    $url = "https://www.hrs.com/en/hotel/" . $this->dataArray['city'] . "/d-" . $this->dataArray['city_id'] . "/$i#container=&locationId=" . $this->dataArray['city_id'] . "&requestUrl=%2Fen%2Fhotel%2F" . $this->dataArray['city'] . "%2Fd-" . $this->dataArray['city_id'] . "&showAlternates=false&toggle=&arrival=" . $this->dataArray['check_in_date'] . "&departure=" . $this->dataArray['check_out_date'] . "&lang=en&minPrice=false&roomType=double&singleRoomCount=0&doubleRoomCount=1&_=1550832580038";
                    $crawler = $client->request('GET', $url);
                    echo $url . "\n";

                    Storage::append('hrs/' . $this->dataArray['request_date'] . '/' . $this->dataArray['city'] . '/url.log', $url . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                    $response = $client->getResponse();

                    if ($response->getStatus() == 200) {


                        $crawler->filter('a.sw-hotel-list__link')->each(function ($node) {
                            $tempData = $node->attr('data-gtm-click');

//                        $da['hotel_id'] = preg_replace('/[^0-9]/', '', $da['link']);

                            $this->dataArray['hotel_id'] = json_decode($tempData)->ecommerce->click->products[0]->id;

                            $adult = 1;
                            global $currency;
                            $url1 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $this->dataArray['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" . $this->dataArray['currency'] . "&startDateDay=22&startDateMonth=02&startDateYear=2019&endDateDay=23&endDateMonth=02&endDateYear=2019&adults=$adult&singleRooms=1&doubleRooms=0&children=0#priceAnchor";
                            $adults = 2;
                            $url2 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $this->dataArray['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" . $this->dataArray['currency'] . "&startDateDay=22&startDateMonth=02&startDateYear=2019&endDateDay=23&endDateMonth=02&endDateYear=2019&adults=$adults&singleRooms=0&doubleRooms=1&children=0#priceAnchor";

                            try {


                                $client2 = new GoutteClient();
                                $client = PhantomClient::getInstance();
                                $client->isLazy(); // Tells the client to wait for all resources before rendering
                                $request = $client->getMessageFactory()->createRequest($url1);
                                $request->setTimeout(5000); // Will render page if this timeout is reached and resources haven't finished loading
                                $response = $client->getMessageFactory()->createResponse();
                                // Send the request
                                $client->send($request, $response);
                                $crawler = new Crawler($response->getContent());
//                                $crawler = $client->request('GET', $url1);
                                $crawler2 = $client2->request('GET', $url2);

                                $dr['all_rooms'][] = $crawler2->filter('table#basket > tbody > tr ')->each(function ($node) {
                                    $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                                    $dr['room_type'] = ($node->count() > 0) ? $node->attr('data-roomtype') : null;
                                    $dr['room_short_description'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
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

                                $dr['all_rooms'][] = $crawler->filter('table#basket > tbody > tr ')->each(function ($node) {
                                    $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                                    $dr['room_type'] = ($node->count() > 0) ? $node->attr('data-roomtype') : null;
                                    $dr['room_short_description'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
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

                                $dh['hotel_name'] = ($crawler->filter('div#detailsHead > h2 > span.title')->count() > 0) ? $crawler->filter('div#detailsHead > h2 > span.title')->text() : null;
                                $dh['hotel_address'] = ($crawler->filter('address.hotelAdress')->count() > 0) ? $crawler->filter('address.hotelAdress')->text() : null;

                                Storage::put('hrs/hotelData.html', $crawler->html());

                                $result = preg_split('/"hotelLocationLatitude":/', $crawler->html());
                                if (count($result) > 1) {
                                    $result_split = explode(',', $result[1]);

                                    $this->dataArray['hotel_latitude'] = str_replace(array('"', ':'), '', $result_split[0]);
                                    $this->dataArray['hotel_longitude'] = str_replace(array('"', ':', 'hotelLocationLongitude'), '', $result_split[1]);

                                }

                                $crawler->filter('div.jsAmenities.equipement.col33')->each(function ($node) {
                                    if ($node->filter('h5')->text() == 'Hotel facilities') {

                                        $this->dataArray['hotel_facilities'] = $node->filter('li')->each(function ($node) {
                                            return trim($node->text());
                                        });
                                    }
                                    if ($node->filter('h5')->text() == 'Room facilities') {
                                        $this->dataArray['room_facilities'] = $node->filter('li')->each(function ($node) {
                                            return trim($node->text());
                                        });
                                    }
                                });
                                $crawler->filter('div.jsServices.equipement.col33')->each(function ($node) {
                                    if ($node->filter('h5')->text() == 'In-house services') {
                                        $this->dataArray['in_house_services'] = $node->filter('li')->each(function ($node) {
                                            return trim($node->text());
                                        });
                                    }
                                });

                                dd($crawler->filter('dl#hotelinformation')->each(function ($node) {
//                                    $this->dataArray['node_hotel_information'] = $node;
//                                    $node->filter('dt > h5')->each(function ($node) {
//
//                                        if ($this->dataArray['node_hotel_information']->filter('dt > h5')->text() == 'Style of hotel and ambience') {
//                                            $this->dataArray['hotel_style_and_ambience'] = $this->dataArray['node_hotel_information']->filter('dd')->text();
//                                        }
//                                        if (trim($this->dataArray['node_hotel_information']->filter('dt > h5')->text()) == 'Hotel data') {
//                                            $this->dataArray['hotel_data'] = $this->dataArray['node_hotel_information']->filter('dd')->text();
//                                        }
//                                        if (trim($this->dataArray['node_hotel_information']->filter('dt > h5')->text()) == 'Accepted payment methods') {
//                                            $this->dataArray['hotel_payment_methods'] = $this->dataArray['node_hotel_information']->filter('li')->each(function ($node) {
//                                                return trim($node->text());
//                                            });
//                                        }
//                                    });
//                                    if (trim($node->filter('dt > h5')->text()) == 'Style of hotel and ambience') {
//                                        $this->dataArray['hotel_style_and_ambience'] = $node->filter('dd')->text();
//                                    }


                                    if (trim($node->filter('dt > h5')->text()) == 'Style of hotel and ambience') {
                                        $this->dataArray['hotel_style_and_ambience'] = $node->filter('dd')->text();
                                    }
                                    if (trim($node->filter('dt > h5')->text()) == 'Hotel data') {
                                        $this->dataArray['hotel_data'] = $node->filter('dd')->text();
                                    }
                                    if (trim($node->filter('dt > h5')->text()) == 'Accepted payment methods') {
                                        $this->dataArray['hotel_payment_methods'] = $node->filter('li')->each(function ($node) {
                                            return trim($node->text());
                                        });
                                    }
                                    $this->dataArray['iteration_i'] = 0;
                                    $this->dataArray['iteration_j'] = 0;

                                    $d1['first'] = $node->filter('dt > h5')->each(function ($node){
                                        $this->dataArray[$node->text()];
                                    });
                                    $d1['body'] = $node->filter('dd')->each(function ($node){
                                        
                                        return $node->text();
                                    });
//                                    $d1['last'] = $node->filter('dt > h5')->last()->text();
                                    return $d1;
                                }));

//                                $this->dataArray['node_hotel_information'] = null;

                                dd($this->dataArray);

                                dd($this->dataArray['in_house_services']);
                                dd('reached');

                                $da['source'] = 'hrs.com';

                                global $city, $cityId, $country;

                                $hid = 'hotel' . $dh['hotel_name'] . 'address' . $dh['hotel_address'];
                                $dh['hid'] = str_replace(' ', '', $hid);
                                if (DB::table('hotels_hrs')->where('hid', '=', $dh['hid'])->doesntExist()) {
                                    $dh['hotel_uid'] = uniqid();
//                                    DB::table('hotels_hrs')->insert([
//                                        'uid' => $dh['hotel_uid'],
//                                        's_no' => 1,
//                                        'name' => $dh['hotel_name'],
//                                        'address' => $dh['hotel_address'],
//                                        'city' => $city,
//                                        'city_id_on_hrs' => $cityId,
//                                        'country' => $country,
//                                        'hid' => $dh['hid'],
//                                        'source' => $da['source'],
//                                        'created_at' => DB::raw('now()'),
//                                        'updated_at' => DB::raw('now()')
//                                    ]);
                                    echo Carbon\Carbon::now()->toDateTimeString() . ' Completed hotel-> ' . $dh['hotel_name'] . "\n";
                                } else {
                                    $resultHid = DB::table('hotels_hrs')->select('uid')->where('hid', '=', $dh['hid'])->get();
                                    $dh['hotel_uid'] = $resultHid[0]->uid;
                                    echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $dh['hotel_name'] . "\n";
                                }

                                foreach ($dr['all_rooms'] as $rooms) {

                                    foreach ($rooms as $room) {


                                        if (isset($room['room']) || isset($room['price'])) {

                                            global $checkOutDate, $checkInDate, $currency;

                                            $requestDate = date("Y-m-d");
                                            $room['room_type'] = isset($room['room_type']) ? $room['room_type'] : null;
                                            if ($room['room_type'] == 'singleroom') {
                                                $adults = 1;
                                            }
                                            if ($room['room_type'] == 'doubleroom') {
                                                $adults = 2;
                                            }
                                            $rid = $requestDate . $checkInDate . $checkOutDate . $dh['hotel_name'] . $room['room'] . $room['room_type'] . $room['room_short_description'] . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults
                                            $rid = str_replace(' ', '', $rid);
                                            if (DB::table('rooms_prices_hrs')->where('rid', '=', $rid)->doesntExist()) {


//                                                DB::table('rooms_prices_hrs')->insert([
//                                                    'uid' => uniqid(),
//                                                    's_no' => 1,
//                                                    'price' => $room['price'],
//                                                    'currency' => $currency,
//                                                    'room' => $room['room'],
//                                                    'room_type' => $room['room_type'],
//                                                    'criteria' => $room['criteria'],
//                                                    'short_description' => $room['room_short_description'],
//                                                    'hotel_uid' => $dh['hotel_uid'],
//                                                    'hotel_name' => $dh['hotel_name'],
//                                                    'number_of_adults_in_room_request' => $adults,
//                                                    'check_in_date' => $checkInDate,
//                                                    'check_out_date' => $checkOutDate,
//                                                    'rid' => $rid,
//                                                    'request_date' => $requestDate,
//                                                    'source' => $da['source'],
//                                                    'created_at' => DB::raw('now()'),
//                                                    'updated_at' => DB::raw('now()')
//                                                ]);
                                                echo Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $dh['hotel_name'] . "\n";
                                            } else {
                                                echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $dh['hotel_name'] . "\n";
                                            }
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                global $city;
                                Storage::append('hrs/' . $city . '/errorSingleHotelAndDb.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                                print($e->getMessage());
                            }


                        });
                    }
                    if ($response->getStatus() != 200) {
                        break;
                    }
                } catch (\Exception $e) {
                    global $city;
                    Storage::append('hrs/' . $city . '/errorMain.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                    print($e->getMessage());
                }
            }


            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
    }
}
