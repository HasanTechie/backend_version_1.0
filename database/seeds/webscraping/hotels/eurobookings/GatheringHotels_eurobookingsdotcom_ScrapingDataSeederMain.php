<?php

use Goutte\Client as GoutteClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Database\Seeder;

class GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function mainRun(array $dataArray)
    {
        //
        session_start();

        global $adults, $currency, $city, $country, $checkInDate, $checkOutDate, $cityId;

        $adults = $dataArray['adults'];
        $currency = $dataArray['currency'];
        $city = $dataArray['city'];
        $country = $dataArray['country'];
        $cityId = $dataArray['city_id'];
        $date = $dataArray['start_date'];
        $approxResults = $dataArray['total_results'];

        $end_date = $dataArray['end_date']; //last checkin date hogi last me

        while (strtotime($date) <= strtotime($end_date)) {


            $checkInDate = $date;

            $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));

            $client = new GoutteClient();

            for ($i = 1; $i <= $approxResults; $i += 15) {


                try {

                    $url = "https://www.eurobookings.com/search.html?q=cur:$currency;frm:9;dsti:$cityId;dstt:1;dsts:$city;start:$checkInDate;end:$checkOutDate;fac:0;stars:;rad:0;wa:0;offset:1;rmcnf:1[$adults,0];sf:1;&offset=$i";
                    Storage::append('eurobookings/' . $city . '/url.log', $url . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                    echo "\n" . $url . "\n";

                    $crawler = $client->request('GET', $url);

                    $response = $client->getResponse();

                    if ($response->getStatus() == 200) {

                        if ($crawler->filter('div#idSearchList > table.clsHotelListAvailable > tr')->count() > 0) {

                            $crawler->filter('div#idSearchList > table.clsHotelListAvailable > tr')->each(function ($node) {

                                global $dh1;
                                $dh1['hotel_eurobooking_id'] = ($node->filter('.clsHotelImageDiv > a:nth-child(3)')->count() > 0) ? $node->filter('.clsHotelImageDiv > a:nth-child(3)')->attr('name') : null;
                                $dh1['hotel_eurobooking_img'] = ($node->filter('.clsHotelImageDiv > img')->count() > 0) ? $node->filter('.clsHotelImageDiv > img')->attr('src') : null;
                                $dh1['hotel_stars_category'] = ($node->filter('.clsHotelInfoBlokBesideImage > span')->count() > 0) ? $node->filter('.clsHotelInfoBlokBesideImage > span')->attr('title') : null;

                                try {
                                    if (!empty($dh1['hotel_eurobooking_id'])) {

                                        $url2 = "https://www.tripadvisor.com/WidgetEmbed-cdspropertydetail?locationId=" . $dh1['hotel_eurobooking_id'] . "&lang=en&partnerId=5644224BD98E429BA8E2FC432FEC674B&display=true";

                                        $client2 = PhantomClient::getInstance();
                                        $client2->isLazy();
                                        $request2 = $client2->getMessageFactory()->createRequest($url2);
                                        $request2->setTimeout(5000);
                                        $response2 = $client2->getMessageFactory()->createResponse();
                                        $client2->send($request2, $response2);
                                        $content2 = $response2->getContent();
                                        $crawler2 = new Crawler($content2);

                                        $dh1['hotel_ratings_on_tripadvisor'] = ($crawler2->filter('.taRating > img')->count() > 0) ? $crawler2->filter('.taRating > img')->attr('alt') : null;
                                        $dh1['hotel_number_of_ratings_on_tripadvisor'] = ($crawler2->filter('.numReviews')->count() > 0) ? $crawler2->filter('.numReviews')->text() : null;
                                        $dh1['hotel_ranking_on_tripadvisor'] = ($crawler2->filter('.popIndex')->count() > 0) ? $crawler2->filter('.popIndex')->text() : null;
                                        $dh1['hotel_badge_on_tripadvisor'] = ($crawler2->filter('.cdsBadge')->count() > 0) ? $crawler2->filter('.cdsBadge')->text() : null;
                                        foreach ($dh1 as $key => $instance) {
                                            if (!is_array($instance)) {
                                                $dh1[$key] = trim(str_replace(array("\r", "\n", "\t"), '', $instance));
                                            }
                                        }

                                        if ($crawler2->filter('dl[name="sortableReviewPair"]')->count() > 0) {

                                            $dh1['hotel_reviews_on_tripadvisor'] = $crawler2->filter('dl[name="sortableReviewPair"]')->each(function ($node) {

                                                $dh1['name'] = ($node->filter('.username')->count() > 0) ? $node->filter('.username')->text() : null;
                                                $dh1['location'] = ($node->filter('.location')->count() > 0) ? $node->filter('.location')->text() : null;
                                                $dh1['trip_type'] = ($node->filter('.tripType')->count() > 0) ? $node->filter('.tripType')->text() : null;
                                                $dh1['review_title'] = ($node->filter('.reviewTitle')->count() > 0) ? $node->filter('.reviewTitle')->text() : null;
                                                $dh1['ratings'] = ($node->filter('div.reviewInfo > .rating > span:nth-child(1)')->count() > 0) ? $node->filter('div.reviewInfo > .rating > span:nth-child(1)')->attr('alt') : null;
                                                $dh1['date'] = ($node->filter('div.reviewInfo > span.date')->count() > 0) ? $node->filter('div.reviewInfo > span.date')->text() : null;
                                                $dh1['review'] = ($node->filter('div.reviewBody > dl > dd:nth-child(2)')->count() > 0) ? $node->filter('div.reviewBody > dl > dd:nth-child(2)')->text() : null;

                                                foreach ($dh1 as $key => $instance) {
                                                    if (!is_array($instance)) {
                                                        $dh1[$key] = trim(str_replace(array("\r", "\n", "\t", "« less"), '', $instance));
                                                    }
                                                }
                                                return $dh1;
                                            });
                                        }
                                    }

                                } catch (\Exception $e) {
                                    global $city;
                                    Storage::append('eurobookings/' . $city . '/errorTripAdvisor.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                                    print($e->getMessage());
                                }


                                if ($node->filter('.clsHotelNameSearchResults')->count() > 0) {


                                    $da['all_data'] = $node->filter('.clsHotelNameSearchResults')->each(function ($node) {

                                        try {
                                            $url2 = $node->attr('href');
                                            $client2 = PhantomClient::getInstance();
                                            $client2->isLazy(); // Tells the client to wait for all resources before rendering
                                            $request2 = $client2->getMessageFactory()->createRequest($url2);
                                            $request2->setTimeout(5000); // Will render page if this timeout is reached and resources haven't finished loading
                                            $response2 = $client2->getMessageFactory()->createResponse();
                                            // Send the request
                                            $client2->send($request2, $response2);
                                            $content2 = $response2->getContent();
                                            $crawler = new Crawler($content2);


                                            if ($crawler->filter('table#idEbAvailabilityRoomsTable > tbody')->count() > 0) {


                                                $rooms = $crawler->filter('table#idEbAvailabilityRoomsTable > tbody')->each(function ($node) {
                                                    $_SESSION['room'] = '';
                                                    $_SESSION['node'] = $node;

                                                    if ($node->filter('tr')->count() > 0) {
                                                        $dr['rooms_prices'] = $node->filter('tr')->each(function ($node1) {

                                                            if ($node1->filter('.clsRoomPhotoWrap > img')->count() > 0) {
                                                                $dr['img'] = $_SESSION['img'] = str_replace('//', '', $node1->filter('.clsRoomPhotoWrap > img')->attr('src'));
                                                            }

                                                            if ($node1->filter('li.clsMoreRoomInfo')->count() > 0) {
                                                                $roomId = str_replace('idEbAvailability', '', $node1->filter('li.clsMoreRoomInfo')->attr('id'));
                                                                $dr['room_facilities'] = $_SESSION['room_facilities'] = $_SESSION['node']->filter('#' . strtolower($roomId) . ' > .clsEbAvailabilityRoomsBlockTextInner > p')->each(function ($node) {
                                                                    return trim($node->text());
                                                                });
                                                            }

                                                            if ($node1->filter('.clsMoreRoomInfoTxt')->count() > 0) {
                                                                $dr['room'] = $_SESSION['room'] = $node1->filter('.clsMoreRoomInfoTxt')->text();
                                                            } else {
                                                                $dr['room'] = null;
                                                            }

                                                            $dr['price'] = ($node1->filter('.clsSortByPrice')->count() > 0) ? $node1->filter('.clsSortByPrice')->text() : null;
                                                            $dr['details'] = ($node1->filter('.clsUspList')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node1->filter('.clsUspList')->text())) : null;

                                                            if ((!empty($dr['details']) && !empty($dr['price'])) || empty($dr['room'])) {
                                                                $dr['room'] = (isset($_SESSION['room']) ? $_SESSION['room'] : null);

                                                            }
                                                            if (empty($dr['img'])) {
                                                                $dr['img'] = (isset($_SESSION['img']) ? $_SESSION['img'] : null);
                                                            }

                                                            if (!empty($dr['price']) && empty($dr['details'])) {
                                                                $dr['details'] = 'Not Available';
                                                            }

                                                            if ($_SESSION['room'] == $dr['room']) {
                                                                if (isset($_SESSION['room_facilities'])) {
                                                                    $dr['room_facilities'] = $_SESSION['room_facilities'];
                                                                }
                                                            }

                                                            return $dr;
                                                        });
                                                    }

                                                    foreach ($dr['rooms_prices'] as $key => $value) {
                                                        if (empty($value['price'])) {
                                                            unset($dr['rooms_prices'][$key]);
                                                        }
                                                    }
                                                    return $dr;
                                                });
                                            }

                                            if (isset($rooms[0]['rooms_prices'])) {

                                                $dr['all_rooms'] = $rooms[0]['rooms_prices'];
                                            } else {
                                                $dr['all_rooms'] = null;
                                            }


                                            if ($crawler->filter('#idEbHotelDetailRooms> p')->count() > 0) {
                                                $hotelInfo = $crawler->filter('#idEbHotelDetailRooms> p')->each(function ($node) {
                                                    return preg_replace('/\s+/', ' ', trim(str_replace(array("\r", "\n", "\t"), '', $node->text())));
                                                });
                                            }

                                            if (isset($hotelInfo[0])) {
                                                $dh['hotel_total_rooms'] = $hotelInfo[0];
                                            } else {
                                                $dh['hotel_total_rooms'] = null;
                                            }

                                            if ($crawler->filter('div#idHotelInfoLazy > table > tbody > tr > td')->count()) {


                                                $dh['hotel_info'] = $crawler->filter('div#idHotelInfoLazy > table > tbody > tr > td')->each(function ($node) {

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
                                                $dh['hotel_info'] = null;
                                            }

                                            if ($crawler->filter('div#idHotelPoliciesLazy > table > tbody')->count() > 0) {
                                                $dh['hotel_policies'] = trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('div#idHotelPoliciesLazy > table > tbody')->text()));
                                            } else {
                                                $dh['hotel_policies'] = null;
                                            }
                                            if ($crawler->filter('div#idHotelFacilitiesLazy > div > ul > li')->count() > 0) {
                                                $dh['hotel_facilities'] = $crawler->filter('div#idHotelFacilitiesLazy > div > ul > li')->each(function ($node) {
                                                    return $node->text();
                                                });
                                            } else {
                                                $dh['hotel_facilities'] = null;
                                            }


                                            $dh['hotel_name'] = ($crawler->filter('.clsEbFloatLeft > h1')->count() > 0) ? trim($crawler->filter('.clsEbFloatLeft > h1')->text()) : null;

                                            if ($crawler->filter('#idQuickDescriptionLazy > p')->count() > 0) {
                                                $dh['hotel_details'] = $crawler->filter('#idQuickDescriptionLazy > p')->each(function ($node) {
                                                    return $node->text();
                                                });
                                            } else {
                                                $dh['hotel_details'] = null;
                                            }
                                            $dh['hotel_address'] = ($crawler->filter('div.header-subtext > div.clsClear')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('div.header-subtext > div.clsClear')->text())) : null;
                                            $dh['default_phone'] = ($crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->text())) : null;

                                            $requestDate = date("Y-m-d");

                                            global $checkOutDate, $checkInDate, $currency, $city, $adults, $cityId, $country, $dh1;

                                            $dr['currency'] = $currency;
                                            $dr['number_of_adults_in_room_request'] = $adults;
                                            $dh['city'] = $city;
                                            $dh['city_id_on_eurobookings'] = $cityId;
                                            $dr['check_in_date'] = $checkInDate;
                                            $dr['check_out_date'] = $checkOutDate;
                                            $dr['request_date'] = $requestDate;
                                            $da['source'] = 'eurobookings.com';

                                            $dh['hotel_eurobooking_id'] = $dh1['hotel_eurobooking_id'];
                                            $dh['hotel_eurobooking_img'] = $dh1['hotel_eurobooking_img'];
                                            $dh['hotel_stars_category'] = $dh1['hotel_stars_category'];
                                            $dh['hotel_ratings_on_tripadvisor'] = $dh1['hotel_ratings_on_tripadvisor'];
                                            $dh['hotel_total_number_of_ratings_on_tripadvisor'] = $dh1['hotel_number_of_ratings_on_tripadvisor'];
                                            $dh['hotel_ranking_on_tripadvisor'] = $dh1['hotel_ranking_on_tripadvisor'];
                                            $dh['hotel_badge_on_tripadvisor'] = $dh1['hotel_badge_on_tripadvisor'];
                                            $dh['hotel_reviews_on_tripadvisor'] = $dh1['hotel_reviews_on_tripadvisor'];

                                            $hid = 'hotel' . $dh['hotel_name'] . 'address' . $dh['hotel_address'];

                                            $dh['hid'] = str_replace(' ', '', $hid);

                                            if (DB::table('hotels_eurobookings')->where('hid', '=', $dh['hid'])->doesntExist()) {
                                                $dh['hotel_uid'] = uniqid();
                                                DB::table('hotels_eurobookings')->insert([
                                                    'uid' => $dh['hotel_uid'],
                                                    's_no' => 1,
                                                    'name' => $dh['hotel_name'],
                                                    'address' => $dh['hotel_address'],
                                                    'total_rooms' => $dh['hotel_total_rooms'],
                                                    'eurobooking_id' => $dh['hotel_eurobooking_id'],
                                                    'photo' => $dh['hotel_eurobooking_img'],
                                                    'stars_category' => $dh['hotel_stars_category'],
                                                    'ratings_on_tripadvisor' => $dh['hotel_ratings_on_tripadvisor'],
                                                    'total_number_of_ratings_on_tripadvisor' => $dh['hotel_total_number_of_ratings_on_tripadvisor'],
                                                    'reviews_on_tripadvisor' => serialize($dh['hotel_reviews_on_tripadvisor']),
                                                    'ranking_on_tripadvisor' => $dh['hotel_ranking_on_tripadvisor'],
                                                    'badge_on_tripadvisor' => $dh['hotel_badge_on_tripadvisor'],
                                                    'details' => serialize($dh['hotel_details']),
                                                    'facilities' => serialize($dh['hotel_facilities']),
                                                    'hotel_info' => serialize($dh['hotel_info']),
                                                    'policies' => $dh['hotel_policies'],
                                                    'city' => $city,
                                                    'city_id_on_eurobookings' => $cityId,
                                                    'country' => $country,
                                                    'hid' => $dh['hid'],
                                                    'source' => $da['source'],
                                                    'created_at' => DB::raw('now()'),
                                                    'updated_at' => DB::raw('now()')
                                                ]);
                                                echo Carbon\Carbon::now()->toDateTimeString() . ' Completed hotel-> ' . $dh['hotel_name'] . "\n";
                                            } else {
                                                $resultHid = DB::table('hotels_eurobookings')->select('uid')->where('hid', '=', $dh['hid'])->get();
                                                $dh['hotel_uid'] = $resultHid[0]->uid;
                                                echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $dh['hotel_name'] . "\n";
                                            }

                                            foreach ($dr['all_rooms'] as $room) {

                                                if (isset($room['room']) || isset($room['price'])) {

                                                    $rid = $requestDate . $checkInDate . $checkOutDate . $dh['hotel_name'] . $room['room'] . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults
                                                    $rid = str_replace(' ', '', $rid);
                                                    if (DB::table('rooms_prices_eurobookings')->where('rid', '=', $rid)->doesntExist()) {

                                                        DB::table('rooms_prices_eurobookings')->insert([
                                                            'uid' => uniqid(),
                                                            's_no' => 1,
                                                            'price' => $room['price'],
                                                            'currency' => $dr['currency'],
                                                            'room' => $room['room'],
                                                            'short_description' => $room['details'],
                                                            'facilities' => serialize($room['room_facilities']),
                                                            'photo' => $room['img'],
                                                            'hotel_uid' => $dh['hotel_uid'],
                                                            'hotel_name' => $dh['hotel_name'],
                                                            'number_of_adults_in_room_request' => $dr['number_of_adults_in_room_request'],
                                                            'check_in_date' => $dr['check_in_date'],
                                                            'check_out_date' => $dr['check_out_date'],
                                                            'rid' => $rid,
                                                            'request_date' => $dr['request_date'],
                                                            'source' => $da['source'],
                                                            'created_at' => DB::raw('now()'),
                                                            'updated_at' => DB::raw('now()')
                                                        ]);
                                                        echo Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $dh['hotel_name'] . "\n";
                                                    } else {
                                                        echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $dh['hotel_name'] . "\n";
                                                    }
                                                }
                                            }

                                        } catch (\Exception $e) {
                                            global $city;

                                            Storage::append('eurobookings/' . $city . '/errorFilteringAndDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                                            print($e->getMessage());
                                        }
                                    });
                                }

                            });
                        }
                    }
                    if ($response->getStatus() != 200) {
                        break;
                    }
                } catch (\Exception $e) {
                    global $city;
                    Storage::append('eurobookings/' . $city . '/errorMain.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                    print($e->getMessage());
                }
            }
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
    }
}

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
                echo Carbon\Carbon::now()->toDateTimeString() . ' ' . $j . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $da['hotel_name'] . "\n";
            } else {
                echo Carbon\Carbon::now()->toDateTimeString() . ' ' . $j . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $da['hotel_name'] . "\n";
            }
        }

    } catch (\Exception $e) {
        print($e->getMessage());
    }
});
*/
