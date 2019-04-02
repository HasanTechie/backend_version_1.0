<?php
use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client as GoutteClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

use Illuminate\Database\Seeder;

class testSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dA = [];

    public function mainRun($data)
    {
        $this->dA = $data;

        $this->dA['proxy'] = 'proxy.proxycrawl.com:9000';
//        $this->setCredentials();
        $this->dA['timeOut'] = 20000;
        $this->dA['request_date'] = date("Y-m-d");
        $this->dA['count_access_denied'] = 0;
        $this->dA['count_not_found'] = 0;
        $this->dA['count_i'] = 1;

        Storage::makeDirectory('hrs/' . $this->dA['request_date']);

        while (0 == 0) {
            try {
                $goutteClient = new GoutteClient();
                $guzzleClient = new GuzzleClient(array(
                    'curl' => [
                        CURLOPT_PROXY => "http://" . $this->dA['proxy'],
                    ]
                ));
                $goutteClient->setClient($guzzleClient);
            } catch (\Exception $e) {
                Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/goutteRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                print($e->getMessage());
            }

            try {
                while (strtotime($this->dA['start_date']) <= strtotime($this->dA['end_date'])) {

                    $this->dA['check_in_date'] = $this->dA['start_date'];

                    $this->dA['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($this->dA['start_date'])));

                    while (0 == 0) {

                        $url = "https://www.hrs.com/en/hotel/" . $this->dA['city'] . "/d-" . $this->dA['city_id'] . "/" . $this->dA['count_i']++ . "#container=&locationId=" . $this->dA['city_id'] . "&requestUrl=%2Fen%2Fhotel%2F" . $this->dA['city'] . "%2Fd-" . $this->dA['city_id'] . "&showAlternates=false&toggle=&arrival=" . $this->dA['check_in_date'] . "&departure=" . $this->dA['check_out_date'] . "&lang=en&minPrice=false&roomType=double&singleRoomCount=0&doubleRoomCount=1";

                        Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/url.log', $url . ' ' . Carbon::now()->toDateTimeString() . "\n");

                        try {
                            $crawler = $goutteClient->request('GET', $url);
                            $response = $goutteClient->getResponse();
                        } catch (\Exception $e) {
                            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/goutteRequestError2.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                            print($e->getMessage());
                        }

                        if ($crawler->filter('title')->count() > 0) {
                            if ($crawler->filter('title')->text() == 'The requested page could not be found') {
                                $this->dA['count_not_found']++;
                                if ($this->dA['count_not_found'] == 2) {
                                    Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'url:' . $url . ';break-reason:The requested page could not be found;count_access_denied:' . $this->dA['count_access_denied'] . ';count_i:' . $this->dA['count_i'] . ';response->getStatus:' . $response->getStatus() . ';' . Carbon::now()->toDateTimeString() . "\n");
                                    break 3;
                                }
                            }
                        }

                        if ($response->getStatus() == 403) {
                            $this->dA['count_i']--;
                            if ($this->dA['count_access_denied'] == 50) {
                                Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'url:' . $url . ';break-reason:' . $crawler->filter('title')->text() . ';count_access_denied:' . $this->dA['count_access_denied'] . ';count_i:' . $this->dA['count_i'] . ';' . Carbon::now()->toDateTimeString() . "\n");
                                $this->dA['count_access_denied'] = 0;
                                break 3;
                            }
                            $this->dA['count_access_denied']++;
                            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/minorBreakReason.log', 'url:' . $url . ';minor-break-reason:' . $crawler->filter('title')->text() . ';count_access_denied:' . $this->dA['count_access_denied'] . ';count_i:' . $this->dA['count_i'] . ';' . Carbon::now()->toDateTimeString() . "\n");
                            break 2;
                        }

                        if ($response->getStatus() == 200) {

//                        Storage::put('hrs/hotels.html', $crawler->html());

                            if ($crawler->filter('a.sw-hotel-list__link')->count() > 0) {

                                $crawler->filter('a.sw-hotel-list__link')->each(function ($node) {

                                    $this->dA['hotel_hrs_image'] = ($node->filter('div.sw-hotel-list__element__image > noscript > img')->count() > 0) ? $node->filter('div.sw-hotel-list__element__image > noscript > img')->attr('src') : null;

                                    $tempData = ($node->count() > 0) ? $node->attr('data-gtm-click') : null;

                                    if (isset(json_decode($tempData)->ecommerce->click->products[0]->id)) {
                                        $this->dA['hotel_id'] = json_decode($tempData)->ecommerce->click->products[0]->id;
                                    }

                                    if (!empty($this->dA['hotel_id'])) {
                                        $adult = 1;
                                        $url1 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $this->dA['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" . $this->dA['currency'] . "&startDateDay=" . date("d", strtotime($this->dA['check_in_date'])) . "&startDateMonth=" . date("m", strtotime($this->dA['check_in_date'])) . "&startDateYear=" . date("Y", strtotime($this->dA['check_in_date'])) . "&endDateDay=" . date("d", strtotime($this->dA['check_out_date'])) . "&endDateMonth=" . date("m", strtotime($this->dA['check_out_date'])) . "&endDateYear=" . date("Y", strtotime($this->dA['check_out_date'])) . "&adults=$adult&singleRooms=1&doubleRooms=0&children=0#priceAnchor";
                                        $adults = 2;
                                        $url2 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $this->dA['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" . $this->dA['currency'] . "&startDateDay=" . date("d", strtotime($this->dA['check_in_date'])) . "&startDateMonth=" . date("m", strtotime($this->dA['check_in_date'])) . "&startDateYear=" . date("Y", strtotime($this->dA['check_in_date'])) . "&endDateDay=" . date("d", strtotime($this->dA['check_out_date'])) . "&endDateMonth=" . date("m", strtotime($this->dA['check_out_date'])) . "&endDateYear=" . date("Y", strtotime($this->dA['check_out_date'])) . "&adults=$adults&singleRooms=0&doubleRooms=1&children=0#priceAnchor";

                                        try {
                                            $this->dA['hotel_url'] = $url2;
//                                            $crawler2 = $this->goutteRequest($url1);
                                            $crawler = $this->phantomRequest($this->dA['hotel_url']);

                                            $this->dA['hotel_hrs_id'] = ($crawler->filter('input[name="hotelnumber"]')->count() > 0) ? $crawler->filter('input[name="hotelnumber"]')->attr('value') : null;

                                            $hotelHRSidDoesntExist = DB::table('hotels_hrs')->where('hrs_id', '=', $this->dA['hotel_hrs_id'])->doesntExist();
                                            if ($hotelHRSidDoesntExist) {
                                                $this->hotelData($crawler);
//                                            $this->googleData();
                                            }

                                        } catch (\Exception $e) {
                                            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorHotelData.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                                            print($e->getMessage());
                                        }

                                        try {

                                            if ($hotelHRSidDoesntExist) {
                                                $this->insertHotelsDataIntoDB();
                                            }
                                            /*else {
                                                $resultHid = DB::table('hotels_hrs')->select('uid', 'name')->where('hrs_id', '=', $this->dA['hotel_hrs_id'])->get();
                                                $hotelUid = (isset($resultHid[0]->uid) ? $resultHid[0]->uid : null);
                                                $this->dA['hotel_name'] = (isset($resultHid[0]->name) ? $resultHid[0]->name : null);
                                                echo Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $this->dA['hotel_name'] . "\n";
                                            }*/

//                                        $this->roomData($crawler, $crawler2);

//                                        if (is_array($this->dA['all_rooms'])) {
//
//                                            foreach ($this->dA['all_rooms'] as $rooms) {
//                                                foreach ($rooms as $room) {
//
//                                                    if (!empty($room['room']) && !empty($room['price']) && !empty($hotelUid)) {
//
//                                                        $rid = $this->dA['request_date'] . $this->dA['check_in_date'] . $this->dA['check_out_date'] . $this->dA['hotel_name'] . $room['room'] . $room['room_type'] . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults
//
//                                                        $rid = str_replace(' ', '', $rid);
//                                                        if (DB::table('rooms_prices_hrs')->where('rid', '=', $rid)->doesntExist()) {
//                                                            DB::table('rooms_prices_hrs')->insert([
//                                                                'uid' => uniqid(),
//                                                                's_no' => 1,
//                                                                'price' => $room['price'],
//                                                                'currency' => $this->dA['currency'],
//                                                                'room' => $room['room'],
//                                                                'room_type' => $room['room_type'],
//                                                                'criteria' => $room['criteria'],
//                                                                'basic_conditions' => serialize($room['room_basic_conditions']),
//                                                                'photo' => $room['room_image'],
//                                                                'short_description' => $room['room_short_description'],
//                                                                'facilities' => serialize($this->dA['room_facilities']),
//                                                                'hotel_uid' => $hotelUid,
//                                                                'hotel_name' => $this->dA['hotel_name'],
//                                                                'hotel_hrs_id' => $this->dA['hotel_hrs_id'],
//                                                                'number_of_adults_in_room_request' => $room['room_adults'],
//                                                                'check_in_date' => $this->dA['check_in_date'],
//                                                                'check_out_date' => $this->dA['check_out_date'],
//                                                                'rid' => $rid,
//                                                                'request_date' => $this->dA['request_date'],
//                                                                'source' => $this->dA['source'],
//                                                                'created_at' => DB::raw('now()'),
//                                                                'updated_at' => DB::raw('now()')
//                                                            ]);
//                                                            echo Carbon::now()->toDateTimeString() . ' Completed in-> ' . $this->dA['check_in_date'] . ' out-> ' . $this->dA['check_out_date'] . ' hotel-> ' . $this->dA['hotel_name'] . "\n";
//                                                        } else {
//                                                            echo Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $this->dA['check_in_date'] . ' out-> ' . $this->dA['check_out_date'] . ' hotel-> ' . $this->dA['hotel_name'] . "\n";
//                                                        }
//                                                    }
//                                                }
//                                            }
//                                        }
//                                        $this->dA['all_rooms'] = null;

                                        } catch (\Exception $e) {
                                            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/ErrorDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                                            print($e->getMessage());
                                        }


                                    }
                                });
                            }
                        }

                    }
                    $this->dA['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($this->dA['start_date'])));
                }
            } catch (\Exception $e) {
                Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorMain.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                print($e->getMessage());
            }
        }
    }

//    protected function setCredentials()
//    {
//        if (rand(0, 1)) {
//            $this->dA['username'] = 'lum-customer-solidps-zone-static-route_err-pass_dyn';
//            $this->dA['password'] = 'azuuy61773vi';
//            $this->dA['port'] = 22225;
//            $this->dA['user_agent'] = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
//            $this->dA['super_proxy'] = 'zproxy.lum-superproxy.io';
//        } else {
//            $this->dA['username'] = 'lum-customer-solidps-zone-allcountriesdatacenterips-route_err-pass_dyn';
//            $this->dA['password'] = 'axqcz3carpam';
//            $this->dA['port'] = 22225;
//            $this->dA['user_agent'] = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
//            $this->dA['super_proxy'] = 'zproxy.lum-superproxy.io';
//        }
//    }

    protected function goutteRequest($url)
    {
        try {
            $goutteClient = new GoutteClient();
            $guzzleClient = new GuzzleClient(array(
                'curl' => [
                    CURLOPT_PROXY => "http://" . $this->dA['proxy'],
//                    CURLOPT_USERAGENT => $this->dA['user_agent'],
//                    CURLOPT_RETURNTRANSFER => 1,
//                    CURLOPT_PROXY => "http://" . $this->dA['super_proxy'] . ":" . $this->dA['port'] . "",
//                    CURLOPT_PROXYUSERPWD => $this->dA['username'] . "-session-" . mt_rand() . ":" . $this->dA['password'] . "",
                ]
            ));
            $goutteClient->setClient($guzzleClient);
            $crawler = $goutteClient->request('GET', $url);
            return $crawler;

        } catch (\Exception $e) {
            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/goutteRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    protected function phantomRequest($url)
    {
        try {
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
//            $client->getEngine()->addOption('--load-images=false');
//            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy']);
//            $client->getEngine()->addOption("--proxy=http://" . $this->dA['super_proxy'] . ":" . $this->dA['port'] . "");
//            $client->getEngine()->addOption("--proxy-auth=" . $this->dA['username'] . "-session-" . mt_rand() . ":" . $this->dA['password'] . "");
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
            $request->setTimeout($this->dA['timeOut']);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());
            return $crawler;

        } catch (\Exception $e) {
            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/phantomRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    protected function hotelData($crawler)
    {
        $this->dA['hotel_name'] = ($crawler->filter('div#detailsHead > h2 > span.title')->count() > 0) ? $crawler->filter('div#detailsHead > h2 > span.title')->text() : null;
        $this->dA['hotel_address'] = ($crawler->filter('address.hotelAdress')->count() > 0) ? $crawler->filter('address.hotelAdress')->text() : null;
        $this->dA['hotel_hrs_id'] = ($crawler->filter('input[name="hotelnumber"]')->count() > 0) ? $crawler->filter('input[name="hotelnumber"]')->attr('value') : null;

        if (count($crawler)) {
            $result = preg_split('/"hotelLocationLatitude":/', $crawler->html());
            if (count($result) > 1) {
                $result_split = explode(',', $result[1]);

                $this->dA['hotel_latitude'] = str_replace(array('"', ':'), '', $result_split[0]);
                $this->dA['hotel_longitude'] = str_replace(array('"', ':', 'hotelLocationLongitude'), '', $result_split[1]);

            }
        }

        if ($crawler->filter('div.jsAmenities.equipement.col33')->count() > 0) {

            $crawler->filter('div.jsAmenities.equipement.col33')->each(function ($node) {
                if ($node->filter('h5')->count() > 0) {
                    if ($node->filter('h5')->text() == 'Hotel facilities') {
                        $this->dA['hotel_facilities'] = ($node->filter('li')->count() > 0) ? $node->filter('li')->each(function ($node) {
                            return trim($node->text());
                        }) : null;
                    }
                }
            });
        }

        if ($crawler->filter('div.jsServices.equipement.col33')->count() > 0) {
            $crawler->filter('div.jsServices.equipement.col33')->each(function ($node) {
                if ($node->filter('h5')->count() > 0) {
                    if ($node->filter('h5')->text() == 'In-house services') {
                        $this->dA['in_house_services'] = ($node->filter('li')->count() > 0) ? $node->filter('li')->each(function ($node) {
                            return trim($node->text());
                        }) : null;
                    }
                }
            });
        }

        if ($crawler->filter('dl#hotelinformation')->count() > 0) {

            $this->dA['hotel_details']['hotel_information'] = $crawler->filter('dl#hotelinformation')->each(function ($node) {
                $d1['heading'] = ($node->filter('dt > h5')->count() > 0) ? $node->filter('dt > h5')->each(function ($node) {
                    return trim($node->text());
                }) : null;

                $d1['body'] = ($node->filter('dd')->count() > 0) ? $node->filter('dd')->each(function ($node) {
                    if ($node->filter('li')->count() > 0) {
                        return $node->filter('li')->each(function ($node) {
                            return trim($node->text());
                        });
                    } else {
                        return trim($node->text());
                    }
                }) : null;
                for ($k = 0; $k < count($d1['heading']); $k++) {
                    $dh[$d1['heading'][$k]] = $d1['body'][$k];
                }
                return $dh;
            });
        }

        if ($crawler->filter('dl#hdInformationMarginal')->count() > 0) {
            $crawler->filter('dl#hdInformationMarginal')->each(function ($node) {
                $dl['body'] = ($node->filter('dd > p')->count() > 0) ? $node->filter('dd > p')->each(function ($node) {
                    $dl['heading'] = ($node->filter('strong')->count() > 0) ? $node->filter('strong')->text() : null;
                    $dl['wholetext'] = ($node->count() > 0) ? $node->text() : null;
                    $dl['body'] = trim(str_replace($dl['heading'], '', $dl['wholetext']));
                    return $dl;
                }) : null;
                $this->dA['hotel_details']['hotel_service_times'] = $dl['body'];
            });
        }

        if ($crawler->filter('div.distances')->count() > 0) {

            $crawler->filter('div.distances')->each(function ($node) {

                $this->dA['hotel_location_details']['location_of_hotel'] = ($node->filter('p#locationEnviromentFull')->count() > 0) ? $node->filter('p#locationEnviromentFull')->text() : null;


                $this->dA['hotel_location_details']['centralTrainAStation'] = ($node->filter('li.centralTrainAStation')->count() > 0) ? $node->filter('li.centralTrainAStation')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['central_train_station'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;


                $this->dA['hotel_location_details']['train'] = ($node->filter('li.train')->count() > 0) ? $node->filter('li.train')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['train'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;


                $this->dA['hotel_location_details']['bus'] = ($node->filter('li.bus')->count() > 0) ? $node->filter('li.bus')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['bus'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dA['hotel_location_details']['highway'] = ($node->filter('li.highway')->count() > 0) ? $node->filter('li.highway')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['highway'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dA['hotel_location_details']['congresscenter'] = ($node->filter('li.congresscenter')->count() > 0) ? $node->filter('li.congresscenter')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['congresscenter'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dA['hotel_location_details']['hotel_city_center_details'] = ($node->filter('li.citycenter')->count() > 0) ? $node->filter('li.citycenter')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['city_center'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dA['hotel_location_details']['nearby_airports'] = ($node->filter('li.airport')->count() > 0) ? $node->filter('li.airport')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['airport'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dA['hotel_location_details']['hotel_parking_details'] = ($node->filter('li.parking')->count() > 0) ? $node->filter('li.parking')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['parking'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dA['hotel_location_details']['sports_leisure_facilities'] = ($node->filter('div.col33.lastCol33 > ul > li')->count() > 0) ? $node->filter('div.col33.lastCol33 > ul > li')->each(function ($node) {
                    return $node->text();
                }) : null;

                if ($node->filter('div.col33')->count() > 0) {
                    $node->filter('div.col33')->each(function ($node) {
                        if ($node->filter('h5')->count() > 0) {
                            if ($node->filter('h5')->text() == 'Surroundings of the hotel') {
                                $this->dA['hotel_location_details']['surroundings_of_the_hotel'] = $node->filter('ul > li')->each(function ($node) {
                                    return $node->text();
                                });
                            }
                        }
                    });
                } else {
                    $this->dA['hotel_location_details']['surroundings_of_the_hotel'] = null;
                }

            });
        }

        $this->dA['ratings_on_hrs'] = ($crawler->filter('div.ratingCircle')->count() > 0) ? $crawler->filter('div.ratingCircle')->text() : null;
        $this->dA['ratings_text_on_hrs'] = ($crawler->filter('div.ratingDescription > strong')->count() > 0 ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('div.ratingDescription > strong')->text())) : null);
        $this->dA['total_number_of_ratings_on_hrs'] = str_replace($this->dA['ratings_text_on_hrs'], '', (($crawler->filter('div.ratingDescription')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('div.ratingDescription')->text())) : null));
    }

    protected function insertHotelsDataIntoDB()
    {
        $hid = $this->dA['hotel_name'] . $this->dA['hotel_address'];
        $this->dA['hid'] = str_replace(' ', '', $hid);

        if (!empty($this->dA['hid']) && DB::table('hotels_hrs')->where('hid', '=', $this->dA['hid'])->doesntExist()) {
            DB::table('hotels_hrs')->insert([
                'uid' => uniqid(),
                's_no' => 1,
                'name' => $this->dA['hotel_name'],
                'address' => $this->dA['hotel_address'],
                'hrs_id' => $this->dA['hotel_hrs_id'],
                'photo' => (!empty($this->dA['hotel_hrs_image']) ? $this->dA['hotel_hrs_image'] : null),
                'ratings_on_hrs' => (isset($this->dA['ratings_on_hrs']) ? $this->dA['ratings_on_hrs'] : null),
                'ratings_text_on_hrs' => (isset($this->dA['ratings_text_on_hrs']) ? $this->dA['ratings_text_on_hrs'] : null),
                'total_number_of_ratings_on_hrs' => (isset($this->dA['total_number_of_ratings_on_hrs']) ? $this->dA['total_number_of_ratings_on_hrs'] : null),
                'ratings_on_google' => (isset($this->dA['ratings_on_google']) ? $this->dA['ratings_on_google'] : null),
                'total_number_of_ratings_on_google' => (isset($this->dA['total_number_of_ratings_on_google']) ? $this->dA['total_number_of_ratings_on_google'] : null),
                'details' => (isset($this->dA['hotel_details']) ? serialize($this->dA['hotel_details']) : null),
                'location_details' => serialize($this->dA['hotel_location_details']),
                'surroundings_of_the_hotel' => serialize($this->dA['hotel_location_details']['surroundings_of_the_hotel']),
                'sports_leisure_facilities' => serialize($this->dA['hotel_location_details']['sports_leisure_facilities']),
                'nearby_airports' => serialize($this->dA['hotel_location_details']['nearby_airports']),
                'facilities' => serialize($this->dA['hotel_facilities']),
                'in_house_services' => serialize($this->dA['in_house_services']),
                'city' => $this->dA['city'],
                'city_id_on_hrs' => $this->dA['city_id'],
                'country_code' => $this->dA['country_code'],
                'latitude_hrs' => (isset($this->dA['hotel_latitude']) ? $this->dA['hotel_latitude'] : null),
                'latitude_google' => (isset($this->dA['google_latitude']) ? $this->dA['google_latitude'] : null),
                'longitude_hrs' => (isset($this->dA['hotel_longitude']) ? $this->dA['hotel_longitude'] : null),
                'longitude_google' => (isset($this->dA['google_longitude']) ? $this->dA['google_longitude'] : null),
                'hid' => $this->dA['hid'],
                'hotel_url_on_hrs' => (isset($this->dA['hotel_url']) ? $this->dA['hotel_url'] : null),
                'all_data_google' => (isset($this->dA['all_data_google']) ? $this->dA['all_data_google'] : null),
                'source' => $this->dA['source'],
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }
    }



    /*protected function googleData()
    {

        if (isset($this->dA['hotel_latitude']) && isset($this->dA['hotel_longitude'])) {

            $key = 'AIzaSyCnBc_5D1PX2OV6M4kJ0v8KJS8_aW6Z6L4';
            $client = new GuzzleClient();
            $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json";

            $input = $this->dA['hotel_name'] . ' ' . $this->dA['city'];

            $input = str_replace('-', ' ', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $input))); // Replaces all special characters.

            $fields = "formatted_address,geometry,name,permanently_closed,photos,place_id,plus_code,types,user_ratings_total,price_level,rating";

            $response = $client->request('GET', "$url?input=$input&inputtype=textquery&fields=$fields&locationbias=circle:200@" . $this->dA['hotel_latitude'] . "," . $this->dA['hotel_longitude'] . "&key=$key");

            if (json_decode($response->getBody())->status != 'ZERO_RESULTS') {

                $response = json_decode($response->getBody())->candidates[0];

                $this->dA['ratings_on_google'] = isset($response->rating) ? $response->rating : null;
                $this->dA['total_number_of_ratings_on_google'] = isset($response->user_ratings_total) ? $response->user_ratings_total : null;
                $this->dA['google_latitude'] = isset($response->geometry->location->lat) ? $response->geometry->location->lat : null;
                $this->dA['google_longitude'] = isset($response->geometry->location->lng) ? $response->geometry->location->lng : null;
                $this->dA['all_data_google'] = serialize($response);

            } else {
                Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/GoogleDataNotFound.log', $input . ' lat:' . $this->dA['hotel_latitude'] . ' lng:' . $this->dA['hotel_longitude']);
            }
        }
    }*/

    /*protected function roomData($crawler, $crawler2)
    {

        if (!empty($crawler2)) {
            if ($crawler2->filter('table#basket > tbody > tr')->count() > 0) {

                $this->dA['all_rooms'][] = $crawler2->filter('table#basket > tbody > tr')->each(function ($node) {
                    $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                    $dr['room_image'] = ($node->filter('td.roomOffer > div.imageWrap > img')->count() > 0) ? $node->filter('td.roomOffer > div.imageWrap > img')->attr('src') : null;
                    $dr['room_basic_conditions'] = ($node->filter('td.roomOffer > div > ul.checkListSmall > li')->count() > 0) ? $node->filter('td.roomOffer > div > ul.checkListSmall > li')->each(function ($node) {
                        return ($node->count() > 0) ? $node->text() : null;
                    }) : null;

                    $dr['room_type'] = 'singleroom';
                    $dr['room_adults'] = 1;
//                    $dr['room_type'] = ($node->count() > 0) ? $node->attr('data-roomtype') : null;
                    $dr['room_short_description'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
//                $dr['price'] = ($node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->last()->text() : null;
                    $dr['full_text_price'] = ($node->filter('td.roomPrice > div > h4.price.standalonePrice')->count() > 0) ? $node->filter('td.roomPrice > div > h4.price.standalonePrice')->text() : null;
                    $dr['price_cents'] = ($node->filter('td.roomPrice > div > h4.price.standalonePrice > sup')->count() > 0) ? $node->filter('td.roomPrice > div > h4.price.standalonePrice > sup')->text() : null;
                    $dr['price'] = str_replace(array($dr['price_cents'], '€'), '', $dr['full_text_price']) . '.' . $dr['price_cents'];
                    $dr['criteria'] = ($node->filter('td.roomPrice > div > div.supplements')->count() > 0) ? $node->filter('td.roomPrice > div > div.supplements')->text() : null;
//                $dr['criteria'] = ($node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->last()->text() : null;
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
            }
        }
        if (!empty($crawler)) {
            if ($crawler->filter('div.jsAmenities.equipement.col33')->count() > 0) {

                $crawler->filter('div.jsAmenities.equipement.col33')->each(function ($node) {
                    if ($node->filter('h5')->count() > 0) {
                        if ($node->filter('h5')->text() == 'Room facilities') {
                            $this->dA['room_facilities'] = ($node->filter('li')->count() > 0) ? $node->filter('li')->each(function ($node) {
                                return trim($node->text());
                            }) : null;
                        }
                    }
                });
            }
            if ($crawler->filter('table#basket > tbody > tr')->count() > 0) {
                $this->dA['all_rooms'][] = $crawler->filter('table#basket > tbody > tr')->each(function ($node) {
                    $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                    $dr['room_image'] = ($node->filter('td.roomOffer > div.imageWrap > img')->count() > 0) ? $node->filter('td.roomOffer > div.imageWrap > img')->attr('src') : null;
                    $dr['room_basic_conditions'] = ($node->filter('td.roomOffer > div > ul.checkListSmall > li')->count() > 0) ? $node->filter('td.roomOffer > div > ul.checkListSmall > li')->each(function ($node) {
                        return ($node->count() > 0) ? $node->text() : null;
                    }) : null;
                    $dr['room_type'] = 'doubleroom';
                    $dr['room_adults'] = 2;
                    $dr['room_short_description'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
//                                                $dr['price'] = ($node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->last()->text() : null;
//                                                $dr['price'] = ($node->filter('td.roomPrice > div > h4')->count() > 0) ? $node->filter('td.roomPrice > div > h4')->text() : null;
                    $dr['full_text_price'] = ($node->filter('td.roomPrice > div > h4.price.standalonePrice')->count() > 0) ? $node->filter('td.roomPrice > div > h4.price.standalonePrice')->text() : null;
                    $dr['price_cents'] = ($node->filter('td.roomPrice > div > h4.price.standalonePrice > sup')->count() > 0) ? $node->filter('td.roomPrice > div > h4.price.standalonePrice > sup')->text() : null;
                    $dr['price'] = str_replace(array($dr['price_cents'], '€'), '', $dr['full_text_price']) . '.' . $dr['price_cents'];
//                                                $dr['criteria'] = ($node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->last()->text() : null;
                    $dr['criteria'] = ($node->filter('td.roomPrice > div > div.supplements')->count() > 0) ? $node->filter('td.roomPrice > div > div.supplements')->text() : null;
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
            }
        }
    }*/
}

//                        $da['hotel_id'] = preg_replace('/[^0-9]/', '', $da['link']);
