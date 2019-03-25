<?php

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client as GoutteClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Database\Seeder;

class GatheringHotels_hrsdotcom_ScrapingDataSeederMainSelected extends Seeder
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
        $this->dataArray['source'] = 'hrs.com';

        while (strtotime($this->dataArray['start_date']) <= strtotime($this->dataArray['end_date'])) {

            $this->dataArray['request_date'] = date("Y-m-d");
            $this->dataArray['check_in_date'] = $this->dataArray['start_date'];

            $this->dataArray['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($this->dataArray['start_date'])));

            $hotelDataArray = [
                'Hotel Emona Aquaeductus' => 79098,
                'Best Western Plus Hotel Universo' => 40828,
                'Hotel Napoleon' => 17442,
                'Hotel Best Roma' => 422725,
                'Hotel Latinum' => 44536,
                'Hotel Porta Maggiore' => 524788,
                'Hotel Novecento' => 377133,
                'Hotel Bled' => 80095,
                'Hotel Milton' => 222310,
                'Hotel Infinito' => 957310,
            ];

            foreach ($hotelDataArray as $hotelID) {

                $adult = 1;
                $url1 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $hotelID . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" . $this->dataArray['currency'] . "&startDateDay=" . date("d", strtotime($this->dataArray['check_in_date'])) . "&startDateMonth=" . date("m", strtotime($this->dataArray['check_in_date'])) . "&startDateYear=" . date("Y", strtotime($this->dataArray['check_in_date'])) . "&endDateDay=" . date("d", strtotime($this->dataArray['check_out_date'])) . "&endDateMonth=" . date("m", strtotime($this->dataArray['check_out_date'])) . "&endDateYear=" . date("Y", strtotime($this->dataArray['check_out_date'])) . "&adults=$adult&singleRooms=1&doubleRooms=0&children=0#priceAnchor";
                $adults = 2;
                $url2 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $hotelID . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" . $this->dataArray['currency'] . "&startDateDay=" . date("d", strtotime($this->dataArray['check_in_date'])) . "&startDateMonth=" . date("m", strtotime($this->dataArray['check_in_date'])) . "&startDateYear=" . date("Y", strtotime($this->dataArray['check_in_date'])) . "&endDateDay=" . date("d", strtotime($this->dataArray['check_out_date'])) . "&endDateMonth=" . date("m", strtotime($this->dataArray['check_out_date'])) . "&endDateYear=" . date("Y", strtotime($this->dataArray['check_out_date'])) . "&adults=$adults&singleRooms=0&doubleRooms=1&children=0#priceAnchor";

                try {

                    $this->dataArray['hotel_url'] = $url2;
                    $client2 = new GoutteClient();
                    $client = PhantomClient::getInstance();
                    $client->isLazy(); // Tells the client to wait for all resources before rendering
                    $request = $client->getMessageFactory()->createRequest($url2);
                    $request->setTimeout(5000); // Will render page if this timeout is reached and resources haven't finished loading
                    $response = $client->getMessageFactory()->createResponse();
                    // Send the request
                    $client->send($request, $response);
                    $crawler = new Crawler($response->getContent());
//                                        Storage::put('hrs/hotelDataDoubleOld.html', $crawler->html()); //to be deleted
                    $crawler2 = $client2->request('GET', $url1);
//                                        Storage::put('hrs/hotelDataSingle.html', $crawler2->html()); //to be deleted

                    $this->dataArray['hotel_hrs_id'] = ($crawler->filter('input[name="hotelnumber"]')->count() > 0) ? $crawler->filter('input[name="hotelnumber"]')->attr('value') : null;

                    $hotelHRSidDoesntExist = DB::table('hotels_hrs')->where('hrs_id', '=', $this->dataArray['hotel_hrs_id'])->doesntExist();
                    if ($hotelHRSidDoesntExist) {
                        $this->hotelData($crawler);
                        $this->googleData();
                    }


                } catch (\Exception $e) {
                    Storage::append('hrs/' . $this->dataArray['request_date'] . '/' . $this->dataArray['city'] . '/errorHotelData.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                    print($e->getMessage());
                }

                try {

                    if ($hotelHRSidDoesntExist) {
                        $hid = $this->dataArray['hotel_name'] . $this->dataArray['hotel_address'];
                        $this->dataArray['hid'] = str_replace(' ', '', $hid);

                        if (!empty($this->dataArray['hid']) && DB::table('hotels_hrs')->where('hid', '=', $this->dataArray['hid'])->doesntExist()) {
                            $hotelUid = uniqid();
                            DB::table('hotels_hrs')->insert([
                                'uid' => $hotelUid,
                                's_no' => 1,
                                'name' => $this->dataArray['hotel_name'],
                                'address' => $this->dataArray['hotel_address'],
                                'hrs_id' => $this->dataArray['hotel_hrs_id'],
                                'photo' => (!empty($this->dataArray['hotel_hrs_image']) ? $this->dataArray['hotel_hrs_image'] : null),
                                'ratings_on_hrs' => (isset($this->dataArray['ratings_on_hrs']) ? $this->dataArray['ratings_on_hrs'] : null),
                                'total_number_of_ratings_on_hrs' => (isset($this->dataArray['total_number_of_ratings_on_hrs']) ? $this->dataArray['total_number_of_ratings_on_hrs'] : null),
                                'ratings_on_google' => (isset($this->dataArray['ratings_on_google']) ? $this->dataArray['ratings_on_google'] : null),
                                'total_number_of_ratings_on_google' => (isset($this->dataArray['total_number_of_ratings_on_google']) ? $this->dataArray['total_number_of_ratings_on_google'] : null),
                                'details' => serialize($this->dataArray['hotel_details']),
                                'location_details' => serialize($this->dataArray['hotel_location_details']),
                                'surroundings_of_the_hotel' => serialize($this->dataArray['hotel_location_details']['surroundings_of_the_hotel']),
                                'sports_leisure_facilities' => serialize($this->dataArray['hotel_location_details']['sports_leisure_facilities']),
                                'nearby_airports' => serialize($this->dataArray['hotel_location_details']['nearby_airports']),
                                'facilities' => serialize($this->dataArray['hotel_facilities']),
                                'in_house_services' => serialize($this->dataArray['in_house_services']),
                                'city' => $this->dataArray['city'],
                                'city_id_on_hrs' => $this->dataArray['city_id'],
                                'country_code' => $this->dataArray['country_code'],
                                'latitude_hrs' => (isset($this->dataArray['hotel_latitude']) ? $this->dataArray['hotel_latitude'] : null),
                                'latitude_google' => (isset($this->dataArray['google_latitude']) ? $this->dataArray['google_latitude'] : null),
                                'longitude_hrs' => (isset($this->dataArray['hotel_longitude']) ? $this->dataArray['hotel_longitude'] : null),
                                'longitude_google' => (isset($this->dataArray['google_longitude']) ? $this->dataArray['google_longitude'] : null),
                                'hid' => $this->dataArray['hid'],
                                'hotel_url_on_hrs' => (isset($this->dataArray['hotel_url']) ? $this->dataArray['hotel_url'] : null),
                                'all_data_google' => (isset($this->dataArray['all_data_google']) ? $this->dataArray['all_data_google'] : null),
                                'source' => $this->dataArray['source'],
                                'created_at' => DB::raw('now()'),
                                'updated_at' => DB::raw('now()')
                            ]);
                            echo Carbon\Carbon::now()->toDateTimeString() . ' Completed hotel-> ' . $this->dataArray['hotel_name'] . "\n";
                        }
                    } else {
                        $resultHid = DB::table('hotels_hrs')->select('uid', 'name')->where('hrs_id', '=', $this->dataArray['hotel_hrs_id'])->get();
                        $hotelUid = $resultHid[0]->uid;
                        $this->dataArray['hotel_name'] = $resultHid[0]->name;
                        echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $this->dataArray['hotel_name'] . "\n";
                    }
                    $this->roomData($crawler, $crawler2);

                    if (is_array($this->dataArray['all_rooms'])) {
                        foreach ($this->dataArray['all_rooms'] as $rooms) {
                            foreach ($rooms as $room) {

                                if (!empty($room['room']) && !empty($room['price']) && !empty($hotelUid)) {

                                    $rid = $this->dataArray['request_date'] . $this->dataArray['check_in_date'] . $this->dataArray['check_out_date'] . $this->dataArray['hotel_name'] . $room['room'] . $room['room_type'] . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults

                                    $rid = str_replace(' ', '', $rid);
                                    if (DB::table('rooms_prices_hrs')->where('rid', '=', $rid)->doesntExist()) {
                                        DB::table('rooms_prices_hrs')->insert([
                                            'uid' => uniqid(),
                                            's_no' => 1,
                                            'price' => $room['price'],
                                            'currency' => $this->dataArray['currency'],
                                            'room' => $room['room'],
                                            'room_type' => $room['room_type'],
                                            'criteria' => $room['criteria'],
                                            'basic_conditions' => serialize($room['room_basic_conditions']),
                                            'photo' => $room['room_image'],
                                            'short_description' => $room['room_short_description'],
                                            'facilities' => serialize($this->dataArray['room_facilities']),
                                            'hotel_uid' => $hotelUid,
                                            'hotel_name' => $this->dataArray['hotel_name'],
                                            'hotel_hrs_id' => $this->dataArray['hotel_hrs_id'],
                                            'number_of_adults_in_room_request' => $room['room_adults'],
                                            'check_in_date' => $this->dataArray['check_in_date'],
                                            'check_out_date' => $this->dataArray['check_out_date'],
                                            'rid' => $rid,
                                            'request_date' => $this->dataArray['request_date'],
                                            'source' => $this->dataArray['source'],
                                            'created_at' => DB::raw('now()'),
                                            'updated_at' => DB::raw('now()')
                                        ]);
                                        echo Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $this->dataArray['check_in_date'] . ' out-> ' . $this->dataArray['check_out_date'] . ' hotel-> ' . $this->dataArray['hotel_name'] . "\n";
                                    } else {
                                        echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $this->dataArray['check_in_date'] . ' out-> ' . $this->dataArray['check_out_date'] . ' hotel-> ' . $this->dataArray['hotel_name'] . "\n";
                                    }
                                }
                            }
                        }
                    }

                    $this->dataArray['all_rooms'] = null;

                } catch (\Exception $e) {
                    Storage::append('hrs/' . $this->dataArray['request_date'] . '/' . $this->dataArray['city'] . '/ErrorDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                    print($e->getMessage());
                }
            }

//                    if ($response->getStatus() != 200) {
//                        break;
//                    }

            $this->dataArray['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($this->dataArray['start_date'])));
        }
    }

    protected function hotelData($crawler)
    {
        $this->dataArray['hotel_name'] = ($crawler->filter('div#detailsHead > h2 > span.title')->count() > 0) ? $crawler->filter('div#detailsHead > h2 > span.title')->text() : null;
        $this->dataArray['hotel_address'] = ($crawler->filter('address.hotelAdress')->count() > 0) ? $crawler->filter('address.hotelAdress')->text() : null;
        $this->dataArray['hotel_hrs_id'] = ($crawler->filter('input[name="hotelnumber"]')->count() > 0) ? $crawler->filter('input[name="hotelnumber"]')->attr('value') : null;

        $result = preg_split('/"hotelLocationLatitude":/', $crawler->html());
        if (count($result) > 1) {
            $result_split = explode(',', $result[1]);

            $this->dataArray['hotel_latitude'] = str_replace(array('"', ':'), '', $result_split[0]);
            $this->dataArray['hotel_longitude'] = str_replace(array('"', ':', 'hotelLocationLongitude'), '', $result_split[1]);

        }

        if ($crawler->filter('div.jsAmenities.equipement.col33')->count() > 0) {

            $crawler->filter('div.jsAmenities.equipement.col33')->each(function ($node) {
                if ($node->filter('h5')->count() > 0) {
                    if ($node->filter('h5')->text() == 'Hotel facilities') {
                        $this->dataArray['hotel_facilities'] = ($node->filter('li')->count() > 0) ? $node->filter('li')->each(function ($node) {
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
                        $this->dataArray['in_house_services'] = ($node->filter('li')->count() > 0) ? $node->filter('li')->each(function ($node) {
                            return trim($node->text());
                        }) : null;
                    }
                }
            });
        }

        if ($crawler->filter('dl#hotelinformation')->count() > 0) {

            $this->dataArray['hotel_details']['hotel_information'] = $crawler->filter('dl#hotelinformation')->each(function ($node) {
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
                $this->dataArray['hotel_details']['hotel_service_times'] = $dl['body'];
            });
        }

        if ($crawler->filter('div.distances')->count() > 0) {

            $crawler->filter('div.distances')->each(function ($node) {

                $this->dataArray['hotel_location_details']['location_of_hotel'] = ($node->filter('p#locationEnviromentFull')->count() > 0) ? $node->filter('p#locationEnviromentFull')->text() : null;


                $this->dataArray['hotel_location_details']['centralTrainAStation'] = ($node->filter('li.centralTrainAStation')->count() > 0) ? $node->filter('li.centralTrainAStation')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['central_train_station'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;


                $this->dataArray['hotel_location_details']['train'] = ($node->filter('li.train')->count() > 0) ? $node->filter('li.train')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['train'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;


                $this->dataArray['hotel_location_details']['bus'] = ($node->filter('li.bus')->count() > 0) ? $node->filter('li.bus')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['bus'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dataArray['hotel_location_details']['highway'] = ($node->filter('li.highway')->count() > 0) ? $node->filter('li.highway')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['highway'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dataArray['hotel_location_details']['congresscenter'] = ($node->filter('li.congresscenter')->count() > 0) ? $node->filter('li.congresscenter')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['congresscenter'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dataArray['hotel_location_details']['hotel_city_center_details'] = ($node->filter('li.citycenter')->count() > 0) ? $node->filter('li.citycenter')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['city_center'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dataArray['hotel_location_details']['nearby_airports'] = ($node->filter('li.airport')->count() > 0) ? $node->filter('li.airport')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['airport'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dataArray['hotel_location_details']['hotel_parking_details'] = ($node->filter('li.parking')->count() > 0) ? $node->filter('li.parking')->each(function ($node) {

                    $da['distance_number'] = $node->filter('span.distanceNumber')->text();
                    $da['distance_dimension'] = $node->filter('span.distanceDimension')->text();
                    $da['parking'] = trim(str_replace(array($da['distance_number'], $da['distance_dimension']), '', $node->text()));
                    $da['wholetext'] = $node->text();

                    return $da;
                }) : null;

                $this->dataArray['hotel_location_details']['sports_leisure_facilities'] = ($node->filter('div.col33.lastCol33 > ul > li')->count() > 0) ? $node->filter('div.col33.lastCol33 > ul > li')->each(function ($node) {
                    return $node->text();
                }) : null;

                if ($node->filter('div.col33')->count() > 0) {
                    $node->filter('div.col33')->each(function ($node) {
                        if ($node->filter('h5')->count() > 0) {
                            if ($node->filter('h5')->text() == 'Surroundings of the hotel') {
                                $this->dataArray['hotel_location_details']['surroundings_of_the_hotel'] = $node->filter('ul > li')->each(function ($node) {
                                    return $node->text();
                                });
                            }
                        }
                    });
                } else {
                    $this->dataArray['hotel_location_details']['surroundings_of_the_hotel'] = null;
                }

            });
        }
        $this->dataArray['ratings_on_hrs'] = ($crawler->filter('div.ratingCircle')->count() > 0) ? $crawler->filter('div.ratingCircle')->text() : null;

        $this->dataArray['total_number_of_ratings_on_hrs'] = ($crawler->filter('div.ratingDescription')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('div.ratingDescription')->text())) : null;

    }

    protected function googleData()
    {

        if (isset($this->dataArray['hotel_latitude']) && isset($this->dataArray['hotel_longitude'])) {

            $key = 'AIzaSyCnBc_5D1PX2OV6M4kJ0v8KJS8_aW6Z6L4';
            $client = new GuzzleClient();
            $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json";

            $input = $this->dataArray['hotel_name'] . ' ' . $this->dataArray['city'];

            $input = str_replace('-', ' ', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $input))); // Replaces all special characters.

            $fields = "formatted_address,geometry,name,permanently_closed,photos,place_id,plus_code,types,user_ratings_total,price_level,rating";

            $response = $client->request('GET', "$url?input=$input&inputtype=textquery&fields=$fields&locationbias=circle:200@" . $this->dataArray['hotel_latitude'] . "," . $this->dataArray['hotel_longitude'] . "&key=$key");

            if (json_decode($response->getBody())->status != 'ZERO_RESULTS') {

                $response = json_decode($response->getBody())->candidates[0];

                $this->dataArray['ratings_on_google'] = isset($response->rating) ? $response->rating : null;
                $this->dataArray['total_number_of_ratings_on_google'] = isset($response->user_ratings_total) ? $response->user_ratings_total : null;
                $this->dataArray['google_latitude'] = isset($response->geometry->location->lat) ? $response->geometry->location->lat : null;
                $this->dataArray['google_longitude'] = isset($response->geometry->location->lng) ? $response->geometry->location->lng : null;
                $this->dataArray['all_data_google'] = serialize($response);

            } else {
                Storage::append('hrs/' . $this->dataArray['request_date'] . '/' . $this->dataArray['city'] . '/GoogleDataNotFound.log', $input . ' lat:' . $this->dataArray['hotel_latitude'] . ' lng:' . $this->dataArray['hotel_longitude']);
            }
        }
    }

    protected function roomData($crawler, $crawler2)
    {

        if (!empty($crawler2)) {
            if ($crawler2->filter('table#basket > tbody > tr')->count() > 0) {

                $this->dataArray['all_rooms'][] = $crawler2->filter('table#basket > tbody > tr')->each(function ($node) {
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
                            $this->dataArray['room_facilities'] = ($node->filter('li')->count() > 0) ? $node->filter('li')->each(function ($node) {
                                return trim($node->text());
                            }) : null;
                        }
                    }
                });
            }
            if ($crawler->filter('table#basket > tbody > tr')->count() > 0) {
                $this->dataArray['all_rooms'][] = $crawler->filter('table#basket > tbody > tr')->each(function ($node) {
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
    }
}

//                        $da['hotel_id'] = preg_replace('/[^0-9]/', '', $da['link']);
