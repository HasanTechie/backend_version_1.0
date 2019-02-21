<?php

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Seeder;

class GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        session_start();
        $_SESSION['room'] = '';

        global $adults, $currency, $city, $checkInDate, $checkOutDate, $cityDist;

        $adults = 2;
        $currency = 'EUR';
        $city = 'Berlin';
        $cityDist = '536';
        $cityTotalResults = 717 - 15;

//        $date = '2019-02-20';
        $date = '2019-03-25';

        $end_date = '2020-02-20'; //last checkin date hogi last me


        while (strtotime($date) <= strtotime($end_date)) {


            $checkInDate = $date;

            $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));

            $goutteClient = new Client();
            $guzzleClient = new GuzzleClient(array(
                'timeout' => 60,
            ));
            $goutteClient->setClient($guzzleClient);
            $client = new Client();

            for ($i = 1; $i <= $cityTotalResults; $i += 15) {

                $url = "https://www.eurobookings.com/search.html?q=cur:$currency;frm:9;dsti:$cityDist;dstt:1;dsts:$city;start:$checkInDate;end:$checkOutDate;fac:0;stars:;rad:0;wa:0;offset:1;rmcnf:1[$adults,0];sf:1;&offset=$i";

                echo "\n" . $url . "\n";

                Storage::append($city . 'url.log', $url . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");

                $crawler = $client->request('GET', $url);

                $crawler->filter('.clsHotelListAvailable > tr')->each(function ($node) {

                    global $da3, $da2;
                    $da3['hotel_eurobooking_id'] = ($node->filter('.clsHotelImageDiv > a:nth-child(3)')->count() > 0) ? $node->filter('.clsHotelImageDiv > a:nth-child(3)')->attr('name') : null;
                    $da3['hotel_eurobooking_img'] = ($node->filter('.clsHotelImageDiv > img')->count() > 0) ? $node->filter('.clsHotelImageDiv > img')->attr('src') : null;
                    $da3['hotel_stars_category'] = ($node->filter('.clsHotelInfoBlokBesideImage > span')->count() > 0) ? $node->filter('.clsHotelInfoBlokBesideImage > span')->attr('title') : null;


                    try {
                        if (!empty($da3['hotel_eurobooking_id'])) {

                            $url2 = "https://www.tripadvisor.com/WidgetEmbed-cdspropertydetail?locationId=" . $da3['hotel_eurobooking_id'] . "&lang=en&partnerId=5644224BD98E429BA8E2FC432FEC674B&display=true";
                            $client2 = new Client();
                            $crawler2 = $client2->request('GET', $url2);

                            $da2['hotel_ratings_on_tripadvisor'] = ($crawler2->filter('.taRating > img')->count() > 0) ? $crawler2->filter('.taRating > img')->attr('alt') : null;
                            $da2['hotel_number_of_ratings_on_tripadvisor'] = ($crawler2->filter('.numReviews')->count() > 0) ? $crawler2->filter('.numReviews')->text() : null;
                            $da2['hotel_ranking_on_tripadvisor'] = ($crawler2->filter('.popIndex')->count() > 0) ? $crawler2->filter('.popIndex')->text() : null;
                            $da2['hotel_badge_on_tripadvisor'] = ($crawler2->filter('.cdsBadge')->count() > 0) ? $crawler2->filter('.cdsBadge')->text() : null;
                            foreach ($da2 as $key => $instance) {
                                if (!is_array($instance)) {
                                    $da2[$key] = trim(str_replace(array("\r", "\n", "\t"), '', $instance));
                                }
                            }
                        }

                    } catch (\Exception $e) {
                        global $city;
                        Storage::append($city . 'errorTripAdvisor.log', $e->getMessage() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                        print($e->getMessage());
                    }


                    $da['all_data'] = $node->filter('.clsHotelNameSearchResults')->each(function ($node) {

                        try {

                            echo $node->attr('href') . "\n";

                            $client = new Client();
                            $crawler = $client->request('GET', $node->attr('href'));

                            Storage::put('hotel.html', $crawler->html());

                            $rooms = $crawler->filter('#idEbAvailabilityRoomsTable')->each(function ($node) {

                                $_SESSION['node'] = $node;
                                $da['rooms_prices'] = $node->filter('#idEbAvailabilityRoomsTable > tr')->each(function ($node1) {


                                    if ($node1->filter('li.clsMoreRoomInfo')->count() > 0) {
                                        $roomId = str_replace('idEbAvailability', '', $node1->filter('li.clsMoreRoomInfo')->attr('id'));
                                        $da['room_facilities'] = $_SESSION['room_facilities'] = $_SESSION['node']->filter('#' . strtolower($roomId) . ' > .clsEbAvailabilityRoomsBlockTextInner > p')->each(function ($node) {
                                            return trim($node->text());
                                        });
                                    }

                                    if ($node1->filter('.clsMoreRoomInfoTxt')->count() > 0) {
                                        $da['room'] = $_SESSION['room'] = $node1->filter('.clsMoreRoomInfoTxt')->text();
                                    } else {
                                        $da['room'] = null;
                                    }

                                    $da['price'] = ($node1->filter('.clsSortByPrice')->count() > 0) ? $node1->filter('.clsSortByPrice')->text() : null;
                                    $da['details'] = ($node1->filter('.clsUspList')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node1->filter('.clsUspList')->text())) : null;

                                    if ((!empty($da['details']) && !empty($da['price'])) || empty($da['room'])) {
                                        $da['room'] = (isset($_SESSION['room']) ? $_SESSION['room'] : null);
                                    }

                                    if (!empty($da['price']) && empty($da['details'])) {
                                        $da['details'] = 'Not Available';
                                    }

                                    if ($_SESSION['room'] == $da['room']) {
                                        if (isset($_SESSION['room_facilities'])) {
                                            $da['room_facilities'] = $_SESSION['room_facilities'];
                                        }
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

                            $hotelInfo = $crawler->filter('#idEbHotelDetailRooms> p')->each(function ($node) {
                                return preg_replace('/\s+/', ' ', trim(str_replace(array("\r", "\n", "\t"), '', $node->text())));
                            });

                            if (isset($hotelInfo[0])) {
                                $da['hotel_total_rooms'] = $hotelInfo[0];
                            } else {
                                $da['hotel_total_rooms'] = null;
                            }

//                            dd($crawler->filter('div.clsTabHotelDetail > div.clsTabContinued > div.clsTabInner > div > a')->attr('href'));

                            $link = $crawler->selectLink('▼ Read More')->link();
//                            dd($link);
                            $crawler = $client->click($link);

                            dd($crawler->extract());

                            Storage::put('hoteldetails.html',$crawler->html());
                            dd($crawler);

                            dd($crawler->filter('div.clsTabHotelDetail > div.clsTabContinued > div.clsTabInner > div > a')->click());
                            $data = $client->click($crawler->filter('div.clsTabHotelDetail > div.clsTabContinued > div.clsTabInner > div > a')->link());

//                            dd($data);
                            Storage::put('hoteldetails.html',$data->html());
//                            dd($client->click($crawler->filter('div.clsTabHotelDetail > div.clsTabContinued > div.clsTabInner > div > a')->link())); //wasted time

                            dd('ra');


                            $da['hotel_name'] = trim($crawler->filter('.clsEbFloatLeft > h1')->text());
                            $da['hotel_short_details'] = trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('#expandQuickDescrp > p')->text()));
                            $da['hotel_address'] = trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.clsEbFloatLeft > .clsClear')->text()));
                            $da['default_phone'] = ($crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $crawler->filter('.clsEbFloatRight.clsBgBarTop > span')->text())) : null;

                            $requestDate = date("Y-m-d");

                            global $checkOutDate, $checkInDate, $currency, $city, $adults, $cityDist, $da2, $da3;

                            $da['hotel_uid'] = uniqid();
                            $da['currency'] = $currency;
                            $da['number_of_adults_in_room_request'] = $adults;
                            $da['city'] = $city;
                            $da['city_dist_on_eurobookings'] = $cityDist;
                            $da['check_in_date'] = $checkInDate;
                            $da['check_out_date'] = $checkOutDate;
                            $da['request_date'] = $requestDate;
                            $da['source'] = 'eurobookings.com';

                            $da['hotel_eurobooking_id'] = $da3['hotel_eurobooking_id'];
                            $da['hotel_eurobooking_img'] = $da3['hotel_eurobooking_img'];
                            $da['hotel_stars_category'] = $da3['hotel_stars_category'];
                            $da['hotel_ratings_on_tripadvisor'] = $da2['hotel_ratings_on_tripadvisor'];
                            $da['hotel_number_of_ratings_on_tripadvisor'] = $da2['hotel_number_of_ratings_on_tripadvisor'];
                            $da['hotel_ranking_on_tripadvisor'] = $da2['hotel_ranking_on_tripadvisor'];
                            $da['hotel_badge_on_tripadvisor'] = $da2['hotel_badge_on_tripadvisor'];

                            dd('reached');
                            foreach ($da['all_rooms'] as $room) {

                                $rid = $rid = 'currentdate' . $requestDate . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelname' . trim(str_replace(' ', '', $da['hotel_name'])) . 'room' . trim(str_replace(' ', '', $room['name'])) . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults

                                if (DB::table('rooms_prices_eurobookings')->where('rid', '=', $rid)->doesntExist()) {

                                    $da['rid'] = $rid;

                                    /*
                                    DB::table('rooms_prices_eurobookings')->insert([
                                        'uid' => uniqid(),
                                        's_no' => 1,
                                        'room' => $room['name'],
                                        'price' => $room['price'],
                                        'currency' => $da['currency'],
                                        'room_short_description' => $room['details'],
                                        'room_facilities' => $room['room_facilities'],
                                        'number_of_adults_in_room_request' => $da['number_of_adults_in_room_request'],
                                        'hotel_uid' => $da['hotel_uid'],
                                        'hotel_name' => $da['hotel_name'],
                                        'hotel_address' => $da['hotel_address'],
                                        'hotel_total_rooms' => $da['hotel_total_rooms'],
                                        'hotel_eurobooking_id' => $da['hotel_eurobooking_id'],
                                        'hotel_eurobooking_img' => $da['hotel_eurobooking_img'],
                                        'hotel_stars_category' => $da['hotel_stars_category'],
                                        'hotel_ratings_on_tripadvisor' => $da['hotel_ratings_on_tripadvisor'],
                                        'hotel_number_of_ratings_on_tripadvisor' => $da['hotel_number_of_ratings_on_tripadvisor'],
                                        'hotel_ranking_on_tripadvisor' => $da['hotel_ranking_on_tripadvisor'],
                                        'hotel_badge_on_tripadvisor' => $da['hotel_badge_on_tripadvisor'],
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
                                    */
                                    echo Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $da['hotel_name'] . "\n";
                                } else {
                                    echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $da['hotel_name'] . "\n";
                                }
                            }

                        } catch (\Exception $e) {
                            global $city;
                            Storage::append($city . 'errorMain.log', $e->getMessage() . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");
                            print($e->getMessage());
                        }
                    });


                });


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
                $url = "https://www.eurobookings.com/search.html?q=cur:$currency;frm:9;dsti:$cityDist;dstt:1;dsts:$city;start:$checkInDate;end:$checkOutDate;fac:0;stars:;rad:0;wa:0;offset:1;rmcnf:1[$adults,0];sf:1;&offset=$i";
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

        global $checkOutDate, $checkInDate, $currency, $city, $adults, $cityDist;

        foreach ($da['all_rooms'] as $room) {


            $rid = $rid = 'currentdate' . $requestDate . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelname' . trim(str_replace(' ', '', $da['hotel_name'])) . 'room' . trim(str_replace(' ', '', $room['name'])) . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults

            if (DB::table('rooms_prices_eurobookings')->where('rid', '=', $rid)->doesntExist()) {


                $da['hotel_uid'] = uniqid();
                $da['currency'] = $currency;
                $da['number_of_adults_in_room_request'] = $adults;
                $da['city'] = $city;
                $da['city_dist_on_eurobookings'] = $cityDist;
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
