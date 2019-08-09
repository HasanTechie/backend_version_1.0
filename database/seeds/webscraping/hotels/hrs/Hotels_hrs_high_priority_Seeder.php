<?php

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client as GoutteClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class Hotels_hrs_high_priority_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dA = [];

    public function run($data)
    {
        try {
            $this->dA = $data;

            $this->dA['proxy'] = 'proxy.proxycrawl.com:9000';
            $this->dA['timeOut'] = 8000;
            $this->dA['request_date'] = date("Y-m-d");
            $this->dA['count_access_denied'] = 0;
            $this->dA['count_unauthorized'] = 0;
            $this->dA['count_not_found'] = 0;
            $this->dA['count_!200'] = 0;
            $this->dA['count_!200b'] = 0;
            $this->dA['count_!200c'] = 0;
            $this->dA['count_i'] = 1;
            $this->dA['currency'] = 'EUR';
            $this->dA['full_break'] = false;

            if (!File::exists(storage_path() . '/app/hrs/' . $this->dA['request_date'] . '/')) {
                Storage::makeDirectory('hrs/' . $this->dA['request_date']);
            }

            $date = date("Y-m-d", strtotime("+5 day"));

            while (strtotime($date) <= strtotime($date)) {
                $this->dA['check_in_date'] = $date;
                $this->dA['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                while (0 == 0) {
                    if ($this->dA['full_break']) {
                        $this->dA['full_break'] = false;
                        break 2;
                    }
                    $this->mainWork(); //data gathering and insertion into DB
                    $this->dA['count_i']++;
                }
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
        } catch (Exception $e) {
            $this->catchException($e, 'errorMain');
        }
    }

    protected function mainWork()
    {

        $hotelManualIds = DB::table('hotel_ids_for_data_gathering')->get();
        $hotelManualIds = json_decode(json_encode($hotelManualIds), true);


        $selectedHotels = array_unique($hotelManualIds, SORT_REGULAR);

        foreach ($selectedHotels as $hotelInstance) {
            $this->dA['hotel_id'] = $hotelInstance['hotel_id'];

            if (!empty($this->dA['hotel_id'])) {
                $adults = 2;
                $this->dA['hotel_url'] = "https://www.hrs.com/hotelData.do?hotelnumber=" . $this->dA['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" . $this->dA['currency'] . "&startDateDay=" . date("d", strtotime($this->dA['check_in_date'])) . "&startDateMonth=" . date("m", strtotime($this->dA['check_in_date'])) . "&startDateYear=" . date("Y", strtotime($this->dA['check_in_date'])) . "&endDateDay=" . date("d", strtotime($this->dA['check_out_date'])) . "&endDateMonth=" . date("m", strtotime($this->dA['check_out_date'])) . "&endDateYear=" . date("Y", strtotime($this->dA['check_out_date'])) . "&adults=$adults&singleRooms=0&doubleRooms=1&children=0";
                restart2:
                $crawler = $this->phantomRequest($this->dA['hotel_url']);
                if ($crawler) {
                    if ($crawler->filter('input[name="hotelnumber"]')->count() > 0) {
                        $this->dA['hotel_hrs_id'] = $crawler->filter('input[name="hotelnumber"]')->attr('value');
                    } else {
                        if ($this->dA['count_!200b'] < 24) {
                            $this->dA['count_!200b']++;
                            goto restart2;
                        } else {
                            $this->dA['hotel_hrs_id'] = null;
                        }
                    }

//                        if (DB::table('hotels_hrs')->where('hrs_id', '=', $this->dA['hotel_hrs_id'])->doesntExist()) {
                    $this->hotelData($crawler);
                    if (!empty($this->dA['hotel_name']) && !empty($this->dA['hotel_address']) && !empty($this->dA['hotel_facilities']) && !empty($this->dA['hotel_location_details'])) {
                        $this->insertHotelsDataIntoDB();
                    } else {
                        if ($this->dA['count_!200c'] < 24) {
                            $this->dA['count_!200c']++;
                            goto restart2;
                        } else {
                            $this->insertHotelsDataIntoDB();
                            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/emptyHotel.log', 'url:' . $this->dA['hotel_url'] . ' ' . ';count_i:' . $this->dA['count_i'] . ';' . Carbon::now()->toDateTimeString() . "\n");
                        }
                    }
//                        }
                }
            }
        }
    }

    protected function catchException($e, $fileName)
    {
        Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/' . $fileName . '.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
        print($e->getMessage());
    }

    protected function phantomRequest($url)
    {
        try {
            restart:
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
            $client->getEngine()->addOption('--load-images=false');
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
//            $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy'][count($this->dA['proxy']) - 1]);
            $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy']);
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
            $request->setTimeout($this->dA['timeOut']);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());

            if ($crawler->filter('title')->count() > 0) { //page could not be found
                if ($crawler->filter('title')->text() == 'The requested page could not be found') {
                    if ($this->dA['count_not_found'] == 4) {
                        Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/hotelsBreakReason1a.log', 'url:' . $url . ' ;break-reason1a:The requested page could not be found;count_access_denied:' . $this->dA['count_access_denied'] . ';count_i:' . $this->dA['count_i'] . ';response->getStatus:' . $response->getStatus() . ';' . Carbon::now()->toDateTimeString() . "\n");
                        $this->dA['full_break'] = true;
                    }
                    Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/hotelsIgnoredReasons2b.log', 'url:' . $url . ' ;break-reason1b:The requested page could not be found;count_access_denied:' . $this->dA['count_access_denied'] . ';count_i:' . $this->dA['count_i'] . ';response->getStatus:' . $response->getStatus() . ';' . Carbon::now()->toDateTimeString() . "\n");
                    $this->dA['count_not_found']++;
                }
            }

            if ($response->getStatus() == 403) { //access denied
                if ($this->dA['count_access_denied'] == 20) {
                    $this->dA['count_access_denied'] = 0;
                    $this->dA['full_break'] = true;
                }
                $this->dA['count_access_denied']++;
            }

            if ($response->getStatus() == 401) { //unauthorized
                if ($this->dA['count_unauthorized'] == 20) {
                    $this->dA['count_unauthorized'] = 0;
                    $this->dA['full_break'] = true;
                }
                $this->dA['count_unauthorized']++;
            }

            if ($response->getStatus() == 200) {
                return $crawler;
            } else {
                if ($response->getStatus() != 0 && $response->getStatus() != 408) {
                    Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/hotelsIgnoredReasons3b.log', 'url:' . $url . ' ;minor-break-reason4b:(getStatus())->' . $response->getStatus() . ';count_i:' . $this->dA['count_i'] . ';count_unauthorized:' . $this->dA['count_unauthorized'] . ';count_access_denied:' . $this->dA['count_access_denied'] . ' ' . Carbon::now()->toDateTimeString() . "\n");
                }
                if ($this->dA['full_break'] == false) {
                    if ($this->dA['count_!200'] > 200) {
                        $this->dA['full_break'] = true;
                    } else {
                        $this->dA['count_!200']++;
                        goto restart;
                    }
                } else {
                    return null;
                }
            }

        } catch (Exception $e) {
            $this->catchException($e, 'phantomRequestError');
        }
    }

    protected function hotelData($crawler)
    {
        try {

            $this->dA['hotel_name'] = ($crawler->filter('div#detailsHead > h2 > span.title')->count() > 0) ? $crawler->filter('div#detailsHead > h2 > span.title')->text() : null;
            $this->dA['hotel_address'] = ($crawler->filter('address.hotelAdress')->count() > 0) ? $crawler->filter('address.hotelAdress')->text() : null;
            $this->dA['hotel_hrs_id'] = ($crawler->filter('input[name="hotelnumber"]')->count() > 0) ? $crawler->filter('input[name="hotelnumber"]')->attr('value') : null;

            if ($crawler->filter('div.starContainer')->count() > 0) {
                $this->dA['hotel_hrs_stars'] = preg_replace('/[^0-9]/', '', $crawler->filter('div.starContainer span:first-child')->attr('class'));
            }

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
                    $da['heading'] = ($node->filter('dt > h5')->count() > 0) ? $node->filter('dt > h5')->each(function ($node) {
                        return trim($node->text());
                    }) : null;

                    $da['body'] = ($node->filter('dd')->count() > 0) ? $node->filter('dd')->each(function ($node) {
                        if ($node->filter('li')->count() > 0) {
                            return $node->filter('li')->each(function ($node) {
                                return trim($node->text());
                            });
                        } else {
                            return trim($node->text());
                        }
                    }) : null;
                    for ($k = 0; $k < count($da['heading']); $k++) {
                        $dh[$da['heading'][$k]] = $da['body'][$k];
                    }
                    return $dh;
                });
            }

            if ($crawler->filter('dl#hdInformationMarginal')->count() > 0) {
                $crawler->filter('dl#hdInformationMarginal')->each(function ($node) {
                    $da['body'] = ($node->filter('dd > p')->count() > 0) ? $node->filter('dd > p')->each(function ($node) {
                        $da['heading'] = ($node->filter('strong')->count() > 0) ? $node->filter('strong')->text() : null;
                        $da['wholetext'] = ($node->count() > 0) ? $node->text() : null;
                        $da['body'] = trim(str_replace($da['heading'], '', $da['wholetext']));
                        return $da;
                    }) : null;
                    $this->dA['hotel_details']['hotel_service_times'] = $da['body'];
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

        } catch (Exception $e) {
            $this->catchException($e, 'errorHotelData');
        }
    }

    protected function insertHotelsDataIntoDB()
    {

        dd('brea');
        try {
            $hid = $this->dA['hotel_name'] . $this->dA['hotel_address'];
            $this->dA['hid'] = substr(str_replace(' ', '', $hid), 0, 254);

            if (!empty($this->dA['hid']) && DB::table('hotels_hrs')->where('hid', '=', $this->dA['hid'])->doesntExist()) {
                DB::table('hotels_hrs')->insert([
                    'name' => $this->dA['hotel_name'],
                    'address' => $this->dA['hotel_address'],
                    'hrs_id' => $this->dA['hotel_hrs_id'],
                    'photo' => (!empty($this->dA['hotel_hrs_image']) ? $this->dA['hotel_hrs_image'] : null),
                    'stars' => (!empty($this->dA['hotel_hrs_stars']) ? $this->dA['hotel_hrs_stars'] : null),
                    'ratings_on_hrs' => (isset($this->dA['ratings_on_hrs']) ? $this->dA['ratings_on_hrs'] : null),
                    'ratings_text_on_hrs' => (isset($this->dA['ratings_text_on_hrs']) ? $this->dA['ratings_text_on_hrs'] : null),
                    'total_number_of_ratings_on_hrs' => (isset($this->dA['total_number_of_ratings_on_hrs']) ? $this->dA['total_number_of_ratings_on_hrs'] : null),
                    'details' => (isset($this->dA['hotel_details']) ? serialize($this->dA['hotel_details']) : null),
                    'location_details' => (isset($this->dA['hotel_location_details']) ? serialize($this->dA['hotel_location_details']) : null),
                    'surroundings_of_the_hotel' => (isset($this->dA['hotel_location_details']['surroundings_of_the_hotel']) ? serialize($this->dA['hotel_location_details']['surroundings_of_the_hotel']) : null),
                    'sports_leisure_facilities' => (isset($this->dA['hotel_location_details']['sports_leisure_facilities']) ? serialize($this->dA['hotel_location_details']['sports_leisure_facilities']) : null),
                    'nearby_airports' => (isset($this->dA['hotel_location_details']['nearby_airports']) ? serialize($this->dA['hotel_location_details']['nearby_airports']) : null),
                    'facilities' => (isset($this->dA['hotel_facilities']) ? serialize($this->dA['hotel_facilities']) : null),
                    'in_house_services' => (isset($this->dA['in_house_services']) ? serialize($this->dA['in_house_services']) : null),
                    'city' => $this->dA['city'],
                    'city_id_on_hrs' => $this->dA['city_id'],
                    'country_code' => $this->dA['country_code'],
                    'latitude_hrs' => (isset($this->dA['hotel_latitude']) ? $this->dA['hotel_latitude'] : null),
                    'longitude_hrs' => (isset($this->dA['hotel_longitude']) ? $this->dA['hotel_longitude'] : null),
                    'hid' => $this->dA['hid'],
                    'hotel_url_on_hrs' => (isset($this->dA['hotel_url']) ? $this->dA['hotel_url'] : null),
                    'source' => $this->dA['source'],
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);
                $this->dA['hotel_name'] = $this->dA['hotel_address'] = $this->dA['hotel_hrs_id'] = $this->dA['hotel_hrs_image'] = $this->dA['ratings_on_hrs'] = $this->dA['ratings_text_on_hrs'] =
                $this->dA['total_number_of_ratings_on_hrs'] = $this->dA['hotel_details'] = $this->dA['hotel_location_details'] = $this->dA['hotel_facilities'] = $this->dA['in_house_services'] =
                $this->dA['hotel_latitude'] = $this->dA['hotel_longitude'] = $this->dA['hid'] = $this->dA['hotel_url'] = null;

//                $this->dA['count_unauthorized'] = 0;
//                $this->dA['count_access_denied'] = 0;
//                $this->dA['count_not_found'] = 0;
//                $this->dA['count_!200'] = 0;
//                $this->dA['count_!200b'] = 0;
//                $this->dA['count_!200c'] = 0;
            } else {
                DB::table('hotels_hrs')
                    ->where('hrs_id', $this->dA['hotel_hrs_id'])
                    ->update([
                        'name' => $this->dA['hotel_name'],
                        'address' => $this->dA['hotel_address'],
                        'hrs_id' => $this->dA['hotel_hrs_id'],
                        'photo' => (!empty($this->dA['hotel_hrs_image']) ? $this->dA['hotel_hrs_image'] : null),
                        'stars' => (!empty($this->dA['hotel_hrs_stars']) ? $this->dA['hotel_hrs_stars'] : null),
                        'ratings_on_hrs' => (isset($this->dA['ratings_on_hrs']) ? $this->dA['ratings_on_hrs'] : null),
                        'ratings_text_on_hrs' => (isset($this->dA['ratings_text_on_hrs']) ? $this->dA['ratings_text_on_hrs'] : null),
                        'total_number_of_ratings_on_hrs' => (isset($this->dA['total_number_of_ratings_on_hrs']) ? $this->dA['total_number_of_ratings_on_hrs'] : null),
                        'details' => (isset($this->dA['hotel_details']) ? serialize($this->dA['hotel_details']) : null),
                        'location_details' => (isset($this->dA['hotel_location_details']) ? serialize($this->dA['hotel_location_details']) : null),
                        'surroundings_of_the_hotel' => (isset($this->dA['hotel_location_details']['surroundings_of_the_hotel']) ? serialize($this->dA['hotel_location_details']['surroundings_of_the_hotel']) : null),
                        'sports_leisure_facilities' => (isset($this->dA['hotel_location_details']['sports_leisure_facilities']) ? serialize($this->dA['hotel_location_details']['sports_leisure_facilities']) : null),
                        'nearby_airports' => (isset($this->dA['hotel_location_details']['nearby_airports']) ? serialize($this->dA['hotel_location_details']['nearby_airports']) : null),
                        'facilities' => (isset($this->dA['hotel_facilities']) ? serialize($this->dA['hotel_facilities']) : null),
                        'in_house_services' => (isset($this->dA['in_house_services']) ? serialize($this->dA['in_house_services']) : null),
                        'city' => $this->dA['city'],
                        'city_id_on_hrs' => $this->dA['city_id'],
                        'country_code' => $this->dA['country_code'],
                        'latitude_hrs' => (isset($this->dA['hotel_latitude']) ? $this->dA['hotel_latitude'] : null),
                        'longitude_hrs' => (isset($this->dA['hotel_longitude']) ? $this->dA['hotel_longitude'] : null),
                        'hid' => $this->dA['hid'],
                        'hotel_url_on_hrs' => (isset($this->dA['hotel_url']) ? $this->dA['hotel_url'] : null),
                        'source' => $this->dA['source'],
                        'updated_at' => DB::raw('now()'),
                    ]);
                Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/AlreadyExisted.log', 'url:' . $this->dA['hotel_url'] . ' ;$hid:' . (!empty($this->dA['hid']) ? $this->dA['hid'] : 'emptyHid') . ';count_i:' . $this->dA['count_i'] . ';' . Carbon::now()->toDateTimeString() . "\n");
            }
            $this->dA['count_unauthorized'] = 0;
            $this->dA['count_access_denied'] = 0;
            $this->dA['count_not_found'] = 0;
            $this->dA['count_!200'] = 0;
            $this->dA['count_!200b'] = 0;
            $this->dA['count_!200c'] = 0;

        } catch (Exception $e) {
            $this->catchException($e, 'ErrorDB');
        }
    }
}

