<?php

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

use Illuminate\Database\Seeder;

class Hotels_eurobookings_SeederPC extends Seeder
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

        $this->dA['proxy'] = 'proxy.proxycrawl.com:9000';

//        $this->setCredentials();
//        $this->dA['timeOut'] = 40000;
        $this->dA['url_array'] = [];
        $this->dA['count_access_denied'] = 0;
        $this->dA['count_same_url'] = 0;
//        $this->dA['count_i'] = 1;
        $this->dA['count_j'] = 0;
        $this->dA['count_l'] = 0;
        $this->dA['count_m'] = 0;
        $this->dA['request_date'] = date("Y-m-d");

        Storage::makeDirectory('eurobookings/' . $this->dA['request_date']);

        while (0 == 0) {
            try {
                $client = PhantomClient::getInstance();
                $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
                $client->getEngine()->addOption('--load-images=false');
                $client->getEngine()->addOption('--ignore-ssl-errors=true');
                $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy']);
//                $client->getEngine()->addOption("--proxy=http://" . $this->dA['super_proxy'] . ":" . $this->dA['port'] . "");
//                $client->getEngine()->addOption("--proxy-auth=" . $this->dA['username'] . "-session-" . mt_rand() . ":" . $this->dA['password'] . "");
                $client->isLazy(); // Tells the client to wait for all resources before rendering

            } catch (\Exception $e) {

                Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/PhantomRequestError2.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
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
                            echo "\n" . 'main :' . $this->dA['url'] . "\n";
                            $request = $client->getMessageFactory()->createRequest($this->dA['url']);
//                            $request->setTimeout($this->dA['timeOut']);
                            $response = $client->getMessageFactory()->createResponse();
                            // Send the request
                            $client->send($request, $response);
                            $crawler = new Crawler($response->getContent());

//                            if (count($crawler)) {
//                                Storage::put('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/eurobookings' . $this->dA['count_i'] . '.html', $crawler->html());
//                                Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/responseCode.log', $this->dA['count_i'] . ' ' . (!empty($response->getStatus()) ? $response->getStatus() : 'noResponse'));
//                            }
//                            $this->dA['count_i']++;

                        } catch (\Exception $e) {
                            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/PhantomRequestError3.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                            print($e->getMessage());
                        }

                        if (isset($response) && isset($crawler)) {


                            if (count($crawler) > 0) {


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

                                if ($response->getStatus() == 200) {

                                    if ($crawler->filter('div.clsPageNavigation')->count() > 0) {
                                        if ($crawler->filter('div.clsPageNavigationPagesActive')->count() > 0) {
                                            echo 'first :' . $this->dA['url'] . "\n";
                                            $this->dA['count_m'] = 0;
                                            $crawler->filter('div.clsPageNavigationPagesActive')->nextAll()->each(function ($node) {
                                                if ($this->dA['count_m'] == 0) {
                                                    if ($node->filter('a')->count() > 0) {
                                                        if (!in_array($node->filter('a')->attr('href'), $this->dA['url_array'])) {
                                                            $this->dA['url'] = $this->dA['url_array'][] = $node->filter('a')->attr('href');
                                                        }
                                                        echo 'secon :' . $this->dA['url'] . "\n\n";
                                                        $this->dA['count_m']++;
                                                    }
                                                }
                                            });
                                        }

                                        if ($crawler->filter('div.clsPageNavigationNextDisabled')->count() > 0) {
                                            if ($crawler->filter('div.clsPageNavigationNextDisabled')->text() == 'Next Page') {
                                                if ($this->dA['count_j'] == 128) {
                                                    Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'url:' . $this->dA['url'] . ';' . 'break-reason:NextPageDisabled;' . Carbon::now()->toDateTimeString() . "\n");
                                                    break 3;
                                                }
                                                $this->dA['count_j']++;
                                            }
                                        }
                                    } else {
                                        if ($this->dA['count_l'] == 128) {
                                            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/breakReason.log', 'url:' . $this->dA['url'] . ';' . 'break-reason:NextPageNotFound;' . Carbon::now()->toDateTimeString() . "\n");
                                            break 3;
                                        }
                                        $this->dA['count_l']++;
                                    }

                                    if ($crawler->filter('div#idSearchList > table.clsHotelListAvailable > tbody > tr')->count() > 0) {

                                        $crawler->filter('div#idSearchList > table.clsHotelListAvailable > tbody > tr')->each(function ($node) {

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
                                                        Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/hotelURL.log', $this->dA['hotel_url'] . ' ' . Carbon::now()->toDateTimeString() . "\n");
                                                        $crawler = $this->phantomRequest($this->dA['hotel_url']);

                                                        if ($this->dA['hotel_eurobooking_id_doesnt_exists']) {

                                                            $this->hotelData($crawler);
//                                                            $this->googleData();
                                                            $this->insertHotelsDataIntoDB();

                                                        }

                                                    } catch (\Exception $e) {

                                                        Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorFilteringAndDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                                                        print($e->getMessage());
                                                    }
                                                });
                                            }

                                        });
                                    }
                                } else {
                                    Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/responseCodes.log', 'url:' . $this->dA['url'] . ';response->getStatus:' . $response->getStatus() . ';' . Carbon::now()->toDateTimeString() . "\n");
                                }
                                $crawler = null;
                            }
                        }
                    } catch (\Exception $e) {
                        Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorMain.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                        print($e->getMessage());
                    }
                }

            }
        }
    }


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
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/goutteRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
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
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/phantomRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    protected function hotelData($crawler)
    {
        if (!empty($crawler)) {


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
                    $this->dA['count_l'] = 0;
                    $this->dA['count_j'] = 0;
                }
            }
        } catch (\Exception $e) {
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorHotelDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
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

                if (count($crawler)) {
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
}
