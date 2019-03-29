<?php

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

use Illuminate\Database\Seeder;

class Hotels_eurobookings_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $dA = [];

    public function mainRun(array $data)
    {
        //
        $this->dA = $data;

        $this->dA['adults'] = 2;

        $this->setCredentials();

        $this->dA['url_array'] = [];
        $this->dA['count_access_denied'] = 0;
        $this->dA['count_same_url'] = 0;
        $this->dA['count_i'] = 1;
        $this->dA['count_j'] = 0;
        $this->dA['count_l'] = 0;
        $this->dA['request_date'] = date("Y-m-d");

        Storage::makeDirectory('eurobookings/' . $this->dA['request_date']);

        while (0 == 0) {
            try {
                $goutteClient = new GoutteClient();
                $guzzleClient = new GuzzleClient(array(
                    'curl' => [
                        CURLOPT_USERAGENT => $this->dA['user_agent'],
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_PROXY => "http://" . $this->dA['super_proxy'] . ":" . $this->dA['port'] . "",
                        CURLOPT_PROXYUSERPWD => $this->dA['username'] . "-session-" . mt_rand() . ":" . $this->dA['password'] . "",
                    ]
                ));
                $goutteClient->setClient($guzzleClient);
            } catch (\Exception $e) {

                Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/goutteRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                print($e->getMessage());
            }

            while (strtotime($this->dA['start_date']) <= strtotime($this->dA['end_date'])) {

                $this->dA['check_in_date'] = $this->dA['start_date'];
                $this->dA['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($this->dA['start_date'])));

                if ($this->dA['k'] == -1) {
                    $this->dA['url'] = "https://www.eurobookings.com/search.html?q=start:" . $this->dA['check_in_date'] . ";end:" . $this->dA['check_out_date'] . ";rmcnf:1[" . $this->dA['adults'] . ",0];dsti:" . $this->dA['city_id'] . ";dstt:1;" . ";frm:9;sort:0_desc;cur:" . $this->dA['currency'] . ";";
                } else {
                    $this->dA['url'] = "https://www.eurobookings.com/search.html?q=start:" . $this->dA['check_in_date'] . ";end:" . $this->dA['check_out_date'] . ";rmcnf:1[" . $this->dA['adults'] . ",0];dsti:" . $this->dA['city_id'] . ";dstt:1;" . ";frm:9;sort:0_desc;cur:" . $this->dA['currency'] . ";stars:" . $this->dA['k'] . ";";
                }

                while (0 == 0) {
                    try {
                        Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/url.log', $this->dA['url'] . ' ' . Carbon::now()->toDateTimeString() . "\n");
//                        $this->dA['url'] = "https://www.eurobookings.com/search.html?q=start:2019-04-10;end:2019-04-11;rmcnf:1[2,0];dsti:70801;dstt:1;dsts:Munich;frm:9;sort:0_desc;cur:EUR;";

                        try {
                            echo 'main :' . $this->dA['url'] . "\n";
                            $crawler = $goutteClient->request('GET', $this->dA['url']);
                            $response = $goutteClient->getResponse();
                        } catch (\Exception $e) {
                            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/goutteRequestError2.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                            print($e->getMessage());
                        }

                        if (isset($response)) {

                            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/responseCode.log', $this->dA['count_i'] . ' ' . $response->getStatus());
                            Storage::put('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/eurobookings' . $this->dA['count_i'] . '.html', ($crawler->count() > 0) ? $crawler->html() : 'empty');
                            $this->dA['count_i']++;

                            if ($response->getStatus() == 403) {
                                if ($this->dA['count_access_denied'] == 50) {
                                    Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'url:' . $this->dA['url'] . ';break-reason:AccessDeniedReached;count_access_denied:' . $this->dA['count_access_denied'] . ';response->getStatus:' . $response->getStatus() . ';' . Carbon::now()->toDateTimeString() . "\n");
                                    $this->dA['count_access_denied'] = 0;
                                    break 3;
                                }
                                Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/minorBreakReason.log', 'url:' . $this->dA['url'] . ';minor-break-reason:AccessDeniedReached;count_access_denied:' . $this->dA['count_access_denied'] . ';response->getStatus:' . $response->getStatus() . ';' . Carbon::now()->toDateTimeString() . "\n");
                                $this->dA['count_access_denied']++;
                                break 2;
                            }

//                            if (in_array($this->dA['url'], $this->dA['url_array'])) {
//                                $this->dA['url'] = end($this->dA['url_array']);
//                                if ($this->dA['count_same_url'] == 8) {
//                                    Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'urlArray:' . serialize($this->dA['url_array']) . 'url:' . $this->dA['url'] . ';' . 'break-reason:sameURL;count_access_denied:' . $this->dA['count_access_denied'] . ';' . Carbon::now()->toDateTimeString() . "\n");
//                                    break 3;
//                                }
//                                $this->dA['count_same_url']++;
//                            }

                            if ($response->getStatus() == 200) {

                                if ($crawler->filter('div.clsPageNavigation')->count() > 0) {
                                    if ($crawler->filter('div.clsPageNavigationPagesActive')->count() > 0) {
                                        echo 'first :' . $this->dA['url'] . "\n";
                                        $this->dA['count_j'] = 0;
                                        $crawler->filter('div.clsPageNavigationPagesActive')->nextAll()->each(function ($node) {
                                            if ($this->dA['count_j'] == 0) {
                                                if ($node->filter('a')->count() > 0) {
                                                    if (!in_array($node->filter('a')->attr('href'), $this->dA['url_array'])) {
                                                        $this->dA['url'] = $this->dA['url_array'][] = $node->filter('a')->attr('href');
                                                    }
                                                    echo 'secon :' . $this->dA['url'] . "\n\n";
                                                    $this->dA['count_j']++;
                                                }
                                            }
                                        });
                                    }

                                    if ($crawler->filter('div.clsPageNavigationNextDisabled')->count() > 0) {
                                        if ($crawler->filter('div.clsPageNavigationNextDisabled')->text() == 'Next Page') {
                                            if ($this->dA['count_j'] == 2) {
                                                Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'url:' . $this->dA['url'] . ';' . 'break-reason:NextPageDisabled;' . Carbon::now()->toDateTimeString() . "\n");
                                                break 3;
                                            }
                                            $this->dA['count_j']++;
                                        }
                                    }
                                } else {
                                    if ($this->dA['count_l'] == 2) {
                                        Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'url:' . $this->dA['url'] . ';' . 'break-reason:NextPageNotFound;' . Carbon::now()->toDateTimeString() . "\n");
                                        break 3;
                                    }
                                    $this->dA['count_l']++;
                                }

                                if ($crawler->filter('div#idSearchList > table.clsHotelListAvailable > tr')->count() > 0) {

                                    $crawler->filter('div#idSearchList > table.clsHotelListAvailable > tr')->each(function ($node) {

                                        $this->dA['hotel_eurobooking_id'] = ($node->filter('.clsHotelImageDiv > a:nth-child(3)')->count() > 0) ? $node->filter('.clsHotelImageDiv > a:nth-child(3)')->attr('name') : null;

                                        $this->dA['hotel_eurobooking_id_doesnt_exists'] = DB::table('hotels_eurobookings')->where('eurobooking_id', '=', $this->dA['hotel_eurobooking_id'])->doesntExist();

                                        if ($this->dA['hotel_eurobooking_id_doesnt_exists']) {
                                            $this->tripAdvisor();
                                            $this->mapsCoordinates();
                                        }

                                        if ($node->filter('.clsHotelNameSearchResults')->count() > 0) {

                                            $node->filter('.clsHotelNameSearchResults')->each(function ($node) {

                                                try {
                                                    $this->dA['hotel_url'] = ($node->count() > 0) ? $node->attr('href') : null;
                                                    $crawler = $this->phantomRequest($this->dA['hotel_url']);

                                                    if ($this->dA['hotel_eurobooking_id_doesnt_exists']) {

                                                        if ($crawler->filter('#idEbHotelDetailRooms> p')->count() > 0) {
                                                            $hotelInfo = $crawler->filter('#idEbHotelDetailRooms> p')->each(function ($node) {
                                                                return preg_replace('/\s+/', ' ', trim(str_replace(array("\r", "\n", "\t"), '', $node->text())));
                                                            });
                                                        }

                                                        if (isset($hotelInfo[0])) {
                                                            $this->dA['hotel_total_rooms'] = $hotelInfo[0];
                                                        } else {
                                                            $this->dA['hotel_total_rooms'] = null;
                                                        }


                                                        if ($crawler->filter('div#idHotelInfoLazy > table > tbody > tr > td')->count()) {
                                                            $this->dA['hotel_info'] = $crawler->filter('div#idHotelInfoLazy > table > tbody > tr > td')->each(function ($node) {
                                                                if ($node->filter('p')->count() > 0) {
                                                                    $dh['heading'] = $node->filter('p')->first()->text();
                                                                    if (trim($dh['heading']) == 'Area information :') {
                                                                        $dh['detailsMeta'] = ($node->filter('p:nth-child(2)')->count() > 0) ? $node->filter('p:nth-child(2)')->text() : null;
                                                                        if ($node->filter('p:nth-child(3)')->count() > 0) {
                                                                            $details = explode("<br>", $node->filter('p:nth-child(3)')->html());
                                                                            foreach ($details as $key => $value) {
                                                                                $dh['all_details'][] = trim($value);
                                                                            }
                                                                        }
                                                                        $dh['nearest_airport'] = ($node->filter('p:nth-child(4)')->count() > 0) ? $node->filter('p:nth-child(4)')->text() : null;
                                                                        $dh['preferred_airport'] = ($node->filter('p:nth-child(5)')->count() > 0) ? $node->filter('p:nth-child(5)')->text() : null;
                                                                    } else {
                                                                        $dh['all_details'] = $node->filter('p')->nextAll()->each(function ($node) {
                                                                            return $node->text();
                                                                        });
                                                                    }
                                                                } else {
                                                                    $dh = null;
                                                                }
                                                                if (isset($dh['all_details'])) {
                                                                    foreach ($dh['all_details'] as $key => $value) {
                                                                        if (empty($value)) {
                                                                            unset($dh['all_details'][$key]);
                                                                        }
                                                                    }
                                                                }
                                                                return $dh;
                                                            });
                                                        } else {
                                                            $this->dA['hotel_info'] = null;
                                                        }
                                                        if ($crawler->filter('div#idHotelPoliciesLazy > table > tbody')->count() > 0) {
                                                            $this->dA['hotel_policies'] = trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('div#idHotelPoliciesLazy > table > tbody')->text()));
                                                        } else {
                                                            $this->dA['hotel_policies'] = null;
                                                        }
                                                        if ($crawler->filter('div#idHotelFacilitiesLazy > div > ul > li')->count() > 0) {
                                                            $this->dA['hotel_facilities'] = $crawler->filter('div#idHotelFacilitiesLazy > div > ul > li')->each(function ($node) {
                                                                return $node->text();
                                                            });
                                                        } else {
                                                            $this->dA['hotel_facilities'] = null;
                                                        }


                                                        $this->dA['hotel_name'] = ($crawler->filter('.clsEbFloatLeft > h1')->count() > 0) ? trim($crawler->filter('.clsEbFloatLeft > h1')->text()) : null;
                                                        $this->dA['hotel_stars_category'] = ($crawler->filter('.clsEbFloatLeft > h1 > span')->count() > 0) ? trim($crawler->filter('.clsEbFloatLeft > h1 > span')->attr('title')) : null;

                                                        $this->dA['hotel_eurobooking_img'] = ($crawler->filter('div.clsEbSmallShadowPhotos > ul > li > a > img')->count() > 0) ? trim($crawler->filter('div.clsEbSmallShadowPhotos > ul > li > a > img')->attr('src')) : null;

                                                        if ($crawler->filter('#idQuickDescriptionLazy > p')->count() > 0) {
                                                            $this->dA['hotel_details'] = $crawler->filter('#idQuickDescriptionLazy > p')->each(function ($node) {
                                                                return $node->text();
                                                            });
                                                        } else {
                                                            $this->dA['hotel_details'] = null;
                                                        }
                                                        $this->dA['hotel_address'] = ($crawler->filter('div.header-subtext > div.clsClear')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('div.header-subtext > div.clsClear')->text())) : null;
                                                        $this->dA['default_phone'] = ($crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->text())) : null;

                                                        $this->dA['BreadcrumbCity'] = ($crawler->filter('a#idBreadcrumbCity')->count() > 0) ? trim($crawler->filter('a#idBreadcrumbCity')->text()) : null;


//                                                    $this->googleData();
                                                        $this->insertHotelsDataIntoDB();

                                                    }
                                                    /*else {
                                                        $resultHid = DB::table('hotels_eurobookings')->select('uid', 'name')->where('eurobooking_id', '=', $this->dA['hotel_eurobooking_id'])->get();
                                                        $hotelUid = (isset($resultHid[0]->uid) ? $resultHid[0]->uid : null);
                                                        $this->dA['hotel_name'] = (isset($resultHid[0]->name) ? $resultHid[0]->name : null);
                                                        echo Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $this->dA['hotel_name'] . "\n";
                                                    }*/


                                                    /*if ($crawler->filter('table#idEbAvailabilityRoomsTable > tbody')->count() > 0 && !empty($hotelUid)) {
                                                        $this->roomsData($crawler);
                                                        $this->dA['all_rooms'] = array_filter($this->dA['all_rooms']);

                                                        if (is_array($this->dA['all_rooms'])) {

                                                            foreach ($this->dA['all_rooms'] as $room) {

                                                                if (!empty($room['room']) || !empty($room['price'])) {

                                                                    try {
                                                                        $rid = $this->dA['request_date'] . $this->dA['check_in_date'] . $this->dA['check_out_date'] . $this->dA['hotel_name'] . $room['room'] . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults
                                                                        $rid = str_replace(' ', '', $rid);
                                                                        $rid = preg_replace('/\s+/u', '', $rid);
                                                                        if (DB::table('rooms_prices_eurobookings')->where('rid', '=', $rid)->doesntExist()) {

                                                                            DB::table('rooms_prices_eurobookings')->insert([
                                                                                'uid' => uniqid(),
                                                                                's_no' => 1,
                                                                                'price' => $room['price'],
                                                                                'currency' => $this->dA['currency'],
                                                                                'room' => $room['room'],
                                                                                'short_description' => (!empty($room['details']) ? serialize($room['details']) : null),
                                                                                'facilities' => serialize($room['room_facilities']),
                                                                                'photo' => $room['img'],
                                                                                'hotel_uid' => $hotelUid,
                                                                                'hotel_eurobooking_id' => $this->dA['hotel_eurobooking_id'],
                                                                                'hotel_name' => $this->dA['hotel_name'],
                                                                                'number_of_adults_in_room_request' => $this->dA['adults'],
                                                                                'check_in_date' => $this->dA['check_in_date'],
                                                                                'check_out_date' => $this->dA['check_out_date'],
                                                                                'rid' => $rid,
                                                                                'request_date' => $this->dA['request_date'],
                                                                                'source' => $this->dA['source'],
                                                                                'created_at' => DB::raw('now()'),
                                                                                'updated_at' => DB::raw('now()')
                                                                            ]);
                                                                            echo Carbon::now()->toDateTimeString() . ' Completed in-> ' . $this->dA['check_in_date'] . ' out-> ' . $this->dA['check_out_date'] . ' hotel-> ' . $this->dA['hotel_name'] . "\n";
                                                                        } else {
                                                                            echo Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $this->dA['check_in_date'] . ' out-> ' . $this->dA['check_out_date'] . ' hotel-> ' . $this->dA['hotel_name'] . "\n";
                                                                        }
                                                                    } catch (\Exception $e) {
                                                                        Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorRoomsDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                                                                        print($e->getMessage());
                                                                    }
                                                                }
                                                            }
                                                            $this->dA['all_rooms'] = null;
                                                        }
                                                    }*/

                                                } catch (\Exception $e) {

                                                    Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorFilteringAndDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                                                    print($e->getMessage());
                                                }
                                            });
                                        }

                                    });
                                }
                            }
                            $crawler = null;
                        }
                    } catch (\Exception $e) {
                        Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorMain.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                        print($e->getMessage());
                    }
                }
                $this->dA['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($this->dA['start_date'])));
            }
        }
    }

    protected function insertHotelsDataIntoDB()
    {
        try {
            $hid = $this->dA['hotel_name'] . $this->dA['hotel_address'];
            $this->dA['hid'] = preg_replace('/\s+/u', '', $hid);
            if (!empty($this->dA['hid']) && (!empty($this->dA['hotel_eurobooking_img']) || !empty($this->dA['hotel_info']))) {
                if (DB::table('hotels_eurobookings')->where('hid', '=', $this->dA['hid'])->doesntExist()) {
                    DB::table('hotels_eurobookings')->insert([
                        'uid' => uniqid(),
                        's_no' => 1,
                        'name' => $this->dA['hotel_name'],
                        'address' => $this->dA['hotel_address'],
                        'total_rooms' => $this->dA['hotel_total_rooms'],
                        'eurobooking_id' => $this->dA['hotel_eurobooking_id'],
                        'photo' => $this->dA['hotel_eurobooking_img'],
                        'stars_category' => $this->dA['hotel_stars_category'],
                        'ratings_on_tripadvisor' => (isset($this->dA['hotel_ratings_on_tripadvisor']) ? $this->dA['hotel_ratings_on_tripadvisor'] : null),
                        'total_number_of_ratings_on_tripadvisor' => (isset($this->dA['hotel_total_number_of_ratings_on_tripadvisor']) ? $this->dA['hotel_total_number_of_ratings_on_tripadvisor'] : null),
                        'reviews_on_tripadvisor' => (isset($this->dA['hotel_reviews_on_tripadvisor']) ? serialize($this->dA['hotel_reviews_on_tripadvisor']) : null),
                        'ranking_on_tripadvisor' => (isset($this->dA['hotel_ranking_on_tripadvisor']) ? $this->dA['hotel_ranking_on_tripadvisor'] : null),
                        'badge_on_tripadvisor' => (isset($this->dA['hotel_badge_on_tripadvisor']) ? $this->dA['hotel_badge_on_tripadvisor'] : null),
//                                                                    'ratings_on_google' => (isset($this->dA['ratings_on_google']) ? $this->dA['ratings_on_google'] : null),
//                                                                    'total_number_of_ratings_on_google' => (isset($this->dA['total_number_of_ratings_on_google']) ? $this->dA['total_number_of_ratings_on_google'] : null),
                        'details' => serialize($this->dA['hotel_details']),
                        'facilities' => serialize($this->dA['hotel_facilities']),
                        'hotel_info' => serialize($this->dA['hotel_info']),
                        'policies' => $this->dA['hotel_policies'],
                        'city' => $this->dA['BreadcrumbCity'],
                        'city_id_on_eurobookings' => $this->dA['city_id'],
                        'country_code' => $this->dA['country_code'],
                        'latitude_eurobookings' => (isset($this->dA['hotel_latitude']) ? $this->dA['hotel_latitude'] : null),
//                                                                    'latitude_google' => (isset($this->dA['google_latitude']) ? $this->dA['google_latitude'] : null),
                        'longitude_eurobookings' => (isset($this->dA['hotel_longitude']) ? $this->dA['hotel_longitude'] : null),
//                                                                    'longitude_google' => (isset($this->dA['google_longitude']) ? $this->dA['google_longitude'] : null),
                        'hid' => $this->dA['hid'],
                        'hotel_url_on_eurobookings' => (isset($this->dA['hotel_url']) ? $this->dA['hotel_url'] : null),
//                                                                    'all_data_google' => (isset($this->dA['all_data_google']) ? $this->dA['all_data_google'] : null),
                        'source' => $this->dA['source'],
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo Carbon::now()->toDateTimeString() . ' Completed hotel-> ' . $this->dA['hotel_name'] . ' ' . $this->dA['city'] . "\n";
                } else {
                    echo Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $this->dA['hotel_name'] . ' ' . $this->dA['city'] . "\n";
                }
            }
        } catch (\Exception $e) {
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorHotelDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    protected function setCredentials()
    {
        if (rand(0, 1)) {
            $this->dA['username'] = 'lum-customer-solidps-zone-static-route_err-pass_dyn';
            $this->dA['password'] = 'azuuy61773vi';
            $this->dA['port'] = 22225;
            $this->dA['user_agent'] = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
            $this->dA['super_proxy'] = 'zproxy.lum-superproxy.io';
        } else {
            $this->dA['username'] = 'lum-customer-solidps-zone-allcountriesdatacenterips-route_err-pass_dyn';
            $this->dA['password'] = 'axqcz3carpam';
            $this->dA['port'] = 22225;
            $this->dA['user_agent'] = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
            $this->dA['super_proxy'] = 'zproxy.lum-superproxy.io';
        }
    }

    protected function tripAdvisor()
    {
        try {
            if (!empty($this->dA['hotel_eurobooking_id'])) {

                $url = "https://www.tripadvisor.com/WidgetEmbed-cdspropertydetail?locationId=" . $this->dA['hotel_eurobooking_id'] . "&lang=en&partnerId=5644224BD98E429BA8E2FC432FEC674B&display=true";

                $crawler = $this->phantomRequest($url);

                $this->dA['hotel_ratings_on_tripadvisor'] = ($crawler->filter('.taRating > img')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.taRating > img')->attr('alt'))) : null;
                $this->dA['hotel_total_number_of_ratings_on_tripadvisor'] = ($crawler->filter('.numReviews')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.numReviews')->text())) : null;
                $this->dA['hotel_ranking_on_tripadvisor'] = ($crawler->filter('.popIndex')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.popIndex')->text())) : null;
                $this->dA['hotel_badge_on_tripadvisor'] = ($crawler->filter('.cdsBadge')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.cdsBadge')->text())) : null;

                if ($crawler->filter('dl[name="sortableReviewPair"]')->count() > 0) {

                    $this->dA['hotel_reviews_on_tripadvisor'] = $crawler->filter('dl[name="sortableReviewPair"]')->each(function ($node) {

                        $dh1['name'] = ($node->filter('.username')->count() > 0) ? $node->filter('.username')->text() : null;
                        $dh1['location'] = ($node->filter('.location')->count() > 0) ? $node->filter('.location')->text() : null;
                        $dh1['trip_type'] = ($node->filter('.tripType')->count() > 0) ? $node->filter('.tripType')->text() : null;
                        $dh1['review_title'] = ($node->filter('.reviewTitle')->count() > 0) ? $node->filter('.reviewTitle')->text() : null;
                        $dh1['ratings'] = ($node->filter('div.reviewInfo > .rating > span:nth-child(1)')->count() > 0) ? $node->filter('div.reviewInfo > .rating > span:nth-child(1)')->attr('alt') : null;
                        $dh1['date'] = ($node->filter('div.reviewInfo > span.date')->count() > 0) ? $node->filter('div.reviewInfo > span.date')->text() : null;
                        $dh1['review'] = ($node->filter('div.reviewBody > dl > dd:nth-child(2)')->count() > 0) ? $node->filter('div.reviewBody > dl > dd:nth-child(2)')->text() : null;
                        foreach ($dh1 as $key => $instance) {
                            if (!is_array($instance)) {
                                $dh1[$key] = trim(str_replace(array("\r", "\n", "\t", "« less"), '', $instance));
                            }
                        }
                        return $dh1;
                    });
                }
            }

        } catch (\Exception $e) {
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorTripAdvisor.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    protected function mapsCoordinates()
    {
        try {
            if (!empty($this->dA['hotel_eurobooking_id'])) {

                $urlMap = "https://www.eurobookings.com/scripts/php/popupGMap.php?intHotelId=" . $this->dA['hotel_eurobooking_id'] . "&lang=en";
                $crawler = $this->phantomRequest($urlMap);

                if ($crawler->count() > 0) {
                    $result = preg_split('/center:/', $crawler->html());
                    if (count($result) > 1) {
                        $result_split = explode(' ', $result[1]);

                        $coordinates = $result_split[1];

                        $coordinates = substr($coordinates, 0, -1);

                        $coordinates = str_replace(array("[", "]"), '', $coordinates);
                        $coordinatesArray = explode(',', $coordinates);

                        $this->dA['hotel_latitude'] = (!empty($coordinatesArray[1]) ? $coordinatesArray[1] : null);
                        $this->dA['hotel_longitude'] = (!empty($coordinatesArray[0]) ? $coordinatesArray[0] : null);
                    }
                }
            }
        } catch (\Exception $e) {
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorMap.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    /*protected function roomsData($crawler)
    {

        $crawler->filter('table#idEbAvailabilityRoomsTable > tbody')->each(function ($node) {
            $this->dA['temp']['room'] = '';
            $this->dA['temp']['node'] = $node;

            if ($node->filter('tr')->count() > 0) {
                $this->dA['all_rooms'] = $node->filter('tr')->each(function ($node1) {

                    if ($node1->filter('.clsRoomPhotoWrap > img')->count() > 0) {
                        $dr['img'] = $this->dA['temp']['img'] = str_replace('//', '', $node1->filter('.clsRoomPhotoWrap > img')->attr('src'));
                    }

                    if ($node1->filter('li.clsMoreRoomInfo')->count() > 0) {
                        $roomId = str_replace('idEbAvailability', '', $node1->filter('li.clsMoreRoomInfo')->attr('id'));
                        $dr['room_facilities'] = $this->dA['temp']['room_facilities'] = $this->dA['temp']['node']->filter('#' . strtolower($roomId) . ' > .clsEbAvailabilityRoomsBlockTextInner > p')->each(function ($node) {
                            return trim($node->text());
                        });
                    }

                    if ($node1->filter('.clsMoreRoomInfoTxt')->count() > 0) {
                        $dr['room'] = $this->dA['temp']['room'] = $node1->filter('.clsMoreRoomInfoTxt')->text();
                    } else {
                        $dr['room'] = null;
                    }

                    $dr['price'] = ($node1->filter('.clsSortByPrice')->count() > 0) ? $node1->filter('.clsSortByPrice')->text() : null;
                    if ($node1->filter('ul.clsUspList  > li')->count() > 0) {
                        $dr['details'] = $node1->filter('ul.clsUspList > li')->each(function ($node1) {
                            return $node1->text();
                        });
                    } else {
                        $dr['details'] = null;
                    }

                    if ((!empty($dr['details']) && !empty($dr['price'])) || empty($dr['room'])) {
                        $dr['room'] = (isset($this->dA['temp']['room']) ? $this->dA['temp']['room'] : null);

                    }
                    if (empty($dr['img'])) {
                        $dr['img'] = (isset($this->dA['temp']['img']) ? $this->dA['temp']['img'] : null);
                    }


                    if ($this->dA['temp']['room'] == $dr['room']) {
                        if (isset($this->dA['temp']['room_facilities'])) {
                            $dr['room_facilities'] = $this->dA['temp']['room_facilities'];
                        }
                    }

                    if (empty($dr['price'])) {
                        return null;
                    } else {
                        return $dr;
                    }
                });
            }
        });
    }*/

    protected function phantomRequest($url)
    {
        try {
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
            $client->getEngine()->addOption('--load-images=false');
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption("--proxy=http://" . $this->dA['super_proxy'] . ":" . $this->dA['port'] . "");
            $client->getEngine()->addOption("--proxy-auth=" . $this->dA['username'] . "-session-" . mt_rand() . ":" . $this->dA['password'] . "");
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());
            return $crawler;

        } catch (\Exception $e) {
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/phantomRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    /*    protected function googleData()
        {
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
                Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/GoogleDataNotFound.log', $input . ' lat:' . $this->dA['hotel_latitude'] . ' lng:' . $this->dA['hotel_longitude']);
            }
        }*/
}

/*        $goutteClient = new GoutteClient();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
            'cookies' => true,
            'allow_redirects' => true
        ));
        $goutteClient->setClient($guzzleClient);*/

/*
//                dd($crawler->filter('div.clsPageNavigationPages > a')->link());
//                $client->click($crawler->filter('div.clsPageNavigationPages > a')->link()); //wasted time
//        $data = $crawler->filter('.clsHotelInfoBlokBesideImage')->each(function ($node) {
            $node->filter('.clsHotelListSmallIconsTxt')->each(function ($node) {
//                dd($node->text());
                $link = $node->filter('.clsHotelExtraIconList.clsCursorPointer');

                $agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.82 Safari/537.36';
                $client = new Client(['HTTP_USER_AGENT' => $agent]);

                $crawler = $client->click($link);

                dd($crawler);
            });
*/

// Storage::put('reviews2.html', $crawler2->html());

/*
            if ($i == 1) {
                $url = "https://www.eurobookings.com/search.html?q=cur:$currency;frm:9;dsti:$cityId;dstt:1;dsts:$city;start:$checkInDate;end:$checkOutDate;fac:0;stars:;rad:0;wa:0;offset:1;rmcnf:1[$adults,0];sf:1;&offset=$i";
            } elseif ($i == 15) {
                $url = "https://www.eurobookings.com/search.html?q=start:2019-03-04;end:2019-03-05;rmcnf:1[2,0];dsti:536;dstt:1;dsts:Berlin;cur:USD;frm:9;sort:0_desc;&offset=$i";
            } else {
                $url = "https://www.eurobookings.com/search.html?q=start:2019-03-04;end:2019-03-05;rmcnf:1[2,0];offset:" . ($i - 15) . ";dsti:536;dstt:1;dsts:Berlin;cur:USD;frm:9;sort:0_desc;&offset=$i";
            }
*/
/*
$crawler->filter('.clsHotelNameSearchResults')->each(function ($node) {

    try {

        $client = new Client();
        $crawler = $client->request('GET', $node->attr('href'));

        $rooms = $crawler->filter('#idEbAvailabilityRoomsTable')->each(function ($node) {

            $da['rooms_prices'] = $node->filter('#idEbAvailabilityRoomsTable > tr')->each(function ($node1) {


                if ($node1->filter('.clsMoreRoomInfoTxt')->count() > 0) {
                    $da['name'] = $_SESSION['room'] = $node1->filter('.clsMoreRoomInfoTxt')->text();
                } else {
                    $da['name'] = null;
                }

                $da['price'] = ($node1->filter('.clsSortByPrice')->count() > 0) ? $node1->filter('.clsSortByPrice')->text() : null;
                $da['details'] = ($node1->filter('.clsUspList')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node1->filter('.clsUspList')->text())) : null;

                if ((!empty($da['details']) && !empty($da['price'])) || empty($da['name'])) {
                    $da['name'] = (isset($_SESSION['room']) ? $_SESSION['room'] : null);
                }

                if (!empty($da['price']) && empty($da['details'])) {
                    $da['details'] = 'Not Available';
                }
                return $da;
            });

            foreach ($da['rooms_prices'] as $key => $value) {
                if (empty($value['price'])) {
                    unset($da['rooms_prices'][$key]);
                }
            }
            return $da;
        });

        $da['all_rooms'] = $rooms[0]['rooms_prices'];

        $da['hotel_info'] = $crawler->filter('#idEbHotelDetailRooms> p')->each(function ($node) {
            return preg_replace('/\s+/', ' ', trim(str_replace(array("\r", "\n", "\t"), '', $node->text())));
        });
        $da['hotel_name'] = trim($crawler->filter('.clsEbFloatLeft > h1')->text());
        $da['hotel_short_details'] = trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('#expandQuickDescrp > p')->text()));
        $da['hotel_address'] = trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.clsEbFloatLeft > .clsClear')->text()));
        $da['default_phone'] = ($crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->text())) : null;


        $requestDate = date("Y-m-d");

        global $checkOutDate, $checkInDate, $currency, $city, $adults, $cityId;

        foreach ($da['all_rooms'] as $room) {


            $rid = $rid = 'currentdate' . $requestDate . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelname' . trim(str_replace(' ', '', $da['hotel_name'])) . 'room' . trim(str_replace(' ', '', $room['name'])) . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults

            if (DB::table('rooms_prices_eurobookings')->where('rid', '=', $rid)->doesntExist()) {


                $da['hotel_uid'] = uniqid();
                $da['currency'] = $currency;
                $da['number_of_adults_in_room_request'] = $adults;
                $da['city'] = $city;
                $da['city_id_on_eurobookings'] = $cityId;
                $da['check_in_date'] = $checkInDate;
                $da['check_out_date'] = $checkOutDate;
                $da['rid'] = $rid;
                $da['request_date'] = $requestDate;
                $da['source'] = 'eurobookings.com';

                if ($result1 = DB::table('rooms_prices_eurobookings')->orderBy('s_no', 'desc')->first()) {
                    $j = $result1->s_no;
                } else {
                    $j = 0;
                }

                DB::table('rooms_prices_eurobookings')->insert([
                    'uid' => uniqid(),
                    's_no' => ++$j,
                    'room' => $room['name'],
                    'price' => $room['price'],
                    'currency' => $da['currency'],
                    'room_short_description' => $room['details'],
                    'number_of_adults_in_room_request' => $da['number_of_adults_in_room_request'],
                    'hotel_uid' => $da['hotel_uid'],
                    'hotel_name' => $da['hotel_name'],
                    'hotel_address' => $da['hotel_address'],
                    'hotel_city' => $da['city'],
                    'check_in_date' => $da['check_in_date'],
                    'check_out_date' => $da['check_out_date'],
                    'rid' => $da['rid'],
                    'request_date' => $da['source'],
                    'all_data' => serialize($da),
                    'source' => $da['source'],
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);
                echo Carbon::now()->toDateTimeString() . ' ' . $j . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $da['hotel_name'] . "\n";
            } else {
                echo Carbon::now()->toDateTimeString() . ' ' . $j . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $da['hotel_name'] . "\n";
            }
        }

    } catch (\Exception $e) {
        print($e->getMessage());
    }
});
*/
