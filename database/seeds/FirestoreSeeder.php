<?php

use GuzzleHttp\Client;
use Google\Cloud\Firestore\FirestoreClient;

use Illuminate\Database\Seeder;

class FirestoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $apiArray = Array(
            "Q7VcIDLY2qRMsGqlwNJVU4UWDMDNaXcv",
            "jjSpfuTbaXPnacVm2YGopbIIaf7A8NFG",
            "dgIOGoQ4AcSAOApXx59AuXI53bKqKTpW",
            "9U3YZf3HN3CCEaAIje3mOGK9o5ouAUyB"
        );

        $k = 0;

        $events = DB::table('events')->select('*')->get();

        $requestCount = 0;

        foreach ($events as $event) {

            $url = "https://app.ticketmaster.com/discovery/v2/events/$event->id.json?&apikey=$apiArray[$k]";


            echo $url . "\n";

            try {
                usleep(500000);
                $client = new Client();
                $response = $client->request('GET', $url);

                $response = json_decode($response->getBody());

            } catch (\Exception $ex) {
                if (!empty($ex)) {
                    echo 'Incompleted' . "\n";
                    $response = '';
                }
            }

            if (!empty($response)) {

                $standardPriceMin = 0;
                $standardPriceMax = 0;
                $standardPriceIncludingFeesMin = 0;
                $standardPriceIncludingFeesMax = 0;
                $currency = '';

                if (isset($response->priceRanges)) {

                    foreach ($response->priceRanges as $price) {
                        if (isset($price->type)) {
                            if ($price->type == 'standard') {
                                if (isset($price->min)) {
                                    $standardPriceMin = $price->min;
                                }
                                if (isset($price->max)) {
                                    $standardPriceMax = $price->max;
                                    $currency = $price->currency;
                                }
                            }
                            if ($price->type == 'standard including fees') {
                                if (isset($price->min)) {
                                    $standardPriceIncludingFeesMin = $price->min;
                                }
                                if (isset($price->max)) {
                                    $standardPriceIncludingFeesMax = $price->max;
                                    $currency = $price->currency;
                                }
                            }
                        }
                    }
                }

                if (isset($response->_embedded->venues[0]->address)) {

                    if (isset($response->_embedded->venues[0]->address)) {
                        if (isset($response->_embedded->venues[0]->address->line1)) {
                            $address = $response->_embedded->venues[0]->address->line1;
                        } else {
                            if (is_object($response->_embedded->venues[0]->address)) {
                                $address = null;
                            } else {
                                $address = $response->_embedded->venues[0]->address;
                            }
                        }
                        if (isset($response->_embedded->venues[0]->address->line2)) {
                            $address = $response->_embedded->venues[0]->address->line1 . $response->_embedded->venues[0]->address->line2;
                        }
                    }
                } else {
                    $address = null;
                }

                DB::table('events')
                    ->where('id', $response->id)
                    ->update([
                        'standard_price_min' => $standardPriceMin,
                        'standard_price_max' => $standardPriceMax,
                        'standard_price_including_fees_min' => $standardPriceIncludingFeesMin,
                        'standard_price_including_fees_max' => $standardPriceIncludingFeesMax,
                        'currency' => $currency,
                        'venue_name' => $response->_embedded->venues[0]->name,
                        'venue_address' => $address,
                        'city' => $response->_embedded->venues[0]->city->name,
                        'all_data' => serialize($response)
                    ]);


                echo ++$requestCount . ' Completed ' . $event->s_no . ' ' . $event->id . "\n";

            }
        }


        dd('reached');

        $db = new FirestoreClient([
//            'keyFilePath' => __DIR__ . '/solidps-backend-data-firebase-adminsdk-8d9sv-9bb2dbfcb0.json'
            'keyFilePath' => __DIR__ . '/solidps-frontend-firebase-adminsdk-6o2qh-81d5c5fe40.json'
        ]);

        $hotels = DB::table('hotels')->where('website', '=', 'novecentohotel.it')->orWhere('website', '=', 'hotelportamaggiore.it')->get();


        foreach ($hotels as $hotel) {

            $properties = $db
                ->collection('properties')//hotles
                ->document($hotel->uid);

            $properties->set([
                'name' => $hotel->name,
                'address' => $hotel->address,
                'city' => $hotel->city,
                'country' => $hotel->country,
                'phone' => $hotel->phone,
                'website' => $hotel->website,
            ]);


            if ($hotel->uid == '5c62bce9f062b') {
                $properties->set([
                    'name' => 'Hotel 4-star',
                    'city' => 'Berlin',
                    'country' => 'Germany',
                ]);

                $dates = DB::table('rooms_prices_hotel_novecento')->select('check_in_date')->distinct('check_in_date')->limit(10)->orderBy('check_in_date')->get();

            } elseif ($hotel->uid == '5c615f19c63f8') {
                $properties->set([
                    'name' => 'Hotel 3-star',
                    'city' => 'Rome',
                    'country' => 'Italy',
                ]);
                $dates = DB::table('rooms_prices_vertical_booking')->select('check_in_date')->distinct('check_in_date')->where('hotel_website', '=', 'hotelportamaggiore.it')->limit(10)->orderBy('check_in_date')->get();
            }

            foreach ($dates as $date) {

                $calendar = $properties->collection('calendar')//dates
                ->document(uniqid());


                $d = date("d", strtotime($date->check_in_date));
                $m = date("m", strtotime($date->check_in_date));
                $y = date("Y", strtotime($date->check_in_date));


                $calendar->set([
                    'date' => Carbon\Carbon::createFromDate($y, $m, $d)
                ]);

                if ($hotel->uid == '5c62bce9f062b') {

                    $rooms = DB::table('rooms_prices_hotel_novecento')->where('check_in_date', '=', $date->check_in_date)->get();
                }

                if ($hotel->uid == '5c615f19c63f8') {

                    $rooms = DB::table('rooms_prices_vertical_booking')->where([
                        ['check_in_date', '=', $date->check_in_date],
                        ['hotel_website', '=', 'hotelportamaggiore.it']
                    ])->get();
                }


                foreach ($rooms as $room) {

                    if ($hotel->uid == '5c62bce9f062b') {
                        $competitor = DB::table('rooms_prices_4_star_fake')->where([
                            ['date', '=', $d . '.' . $m . '.' . $y],
                            ['type', '=', $room->room],
                            ['sales_type', '=', 'Economy'],
                        ])->first();

                        if (!empty($competitor)) {
                            $assets = $calendar
                                ->collection('assets')//rooms
                                ->document($room->uid);

                            $assets->set([
                                'name' => $room->room,
                                'room_description' => $room->room_description,
                                'room_capacity' => $room->number_of_adults_in_room_request . ' number of adults'
                            ]);


                            $options = $assets
                                ->collection('options')//options
                                ->document(uniqid());

                            $options =
                                $options->set([
                                    'real_price' => (double)trim(str_replace(',', '.', str_replace('EUR', '', $room->display_price))),
                                    'competitor_price' => (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_now))),
                                    'suggested_price' => (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_should))),
                                    'hint' => $competitor->action,
                                    'name' => 'Normal'
                                ]);
                        }
                    }

                    if ($hotel->uid == '5c615f19c63f8') {

                        if (
                            (($room->room == 'STANDARD QUADRUPLE ROOM') && ($room->number_of_adults_in_room_request == 4))
                            || (($room->room == 'DELUXE QUADRUPLE ROOM') && ($room->number_of_adults_in_room_request == 4))
                            || (($room->room == 'STANDARD TRIPLE ROOM') && ($room->number_of_adults_in_room_request == 3))
                            || (($room->room == 'DELUXE TRIPLE ROOM') && ($room->number_of_adults_in_room_request == 3))
                            || (($room->room == 'STANDARD DOUBLE/TWIN ROOM') && ($room->number_of_adults_in_room_request == 2))
                            || (($room->room == 'DELUXE DOUBLE/TWIN ROOM') && ($room->number_of_adults_in_room_request == 2))
                            || (($room->room == 'STANDARD SINGLE ROOM') && ($room->number_of_adults_in_room_request == 1))
                            || (($room->room == 'DELUXE SINGLE ROOM') && ($room->number_of_adults_in_room_request == 1))
                        ) {
                            $competitor = DB::table('rooms_prices_3_star_fake')->where([
                                ['date', '=', $d . '.' . $m . '.' . $y],
                                ['type', '=', $room->room],
                            ])->first();


                            if (!empty($competitor)) {
                                $assets = $calendar
                                    ->collection('assets')//rooms
                                    ->document($room->uid);

                                $assets->set([
                                    'name' => $room->room,
                                    'room_description' => $room->room_description,
                                    'room_capacity' => $room->number_of_adults_in_room_request . ' number of adults'
                                ]);


                                $options = $assets
                                    ->collection('options')//options
                                    ->document(uniqid());

                                $options =
                                    $options->set([
                                        'real_price' => (double)trim(str_replace(',', '.', str_replace('EUR', '', $room->display_price))),
                                        'competitor_price' => (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_now))),
                                        'suggested_price' => (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_should))),
                                        'hint' => $competitor->action,
                                        'name' => 'Normal'
                                    ]);
                            }
                        }
                    }


                    //
                }
            }

        }

    }
}


/*
 // For Specific City
        $k = 0;



        $j = 0;
        $k = 0;

        $requestCount = 0;


        for ($i = 0; $i <= 5; $i++) {

//            if ($i % 5 == 0 && $i != 0) {
//                $k++;
//            }

            $url = "https://app.ticketmaster.com/discovery/v2/events.json?city=Rome&apikey=$apiArray[$k]&page=$i&size=200";

            echo $url . "\n";

            try {
//                usleep(500000);
                $client = new Client();
                $response = '';
                $response = $client->request('GET', $url);

                $response = json_decode($response->getBody());

            } catch (\Exception $ex) {
                if (!empty($ex)) {
                    echo 'Incompleted' . "\n";
                    $response = '';
                }
            }

            if (!empty($response)) {

                if ((isset($response->_embedded->events)) && ($response->page->totalElements > 0)) {

                    foreach ($response->_embedded->events as $event) {

                        $standardPriceMin = 0;
                        $standardPriceMax = 0;
                        $standardPriceIncludingFeesMin = 0;
                        $standardPriceIncludingFeesMax = 0;

                        if (isset($event->priceRanges)) {


                            foreach ($event->priceRanges as $price) {
                                if (isset($price->type)) {
                                    if ($price->type == 'standard') {
                                        if (isset($price->max)) {
                                            $standardPriceMax = $price->max;
                                        }
                                    }
                                    if ($price->type == 'standard including fees') {
                                        if (isset($price->max)) {
                                            $standardPriceIncludingFeesMax = $price->max;
                                        }
                                    }
                                }
                            }
                        }

//                                if (!(DB::table('events')->where('id', '=', $event->id)->exists())) {

                        DB::table('events')->insert([
                            'uid' => uniqid(),
                            's_no' => ++$j,

                            'name' => isset($event->name) ? ($event->name) : null,
                            'id' => isset($event->id) ? ($event->id) : null,
                            'url' => isset($event->url) ? ($event->url) : null,
                            'standard_price_min' => $standardPriceMin,
                            'standard_price_max' => $standardPriceMax,
                            'standard_price_including_fees_min' => $standardPriceIncludingFeesMin,
                            'standard_price_including_fees_max' => $standardPriceIncludingFeesMax,
                            'city' => 'Rome',
                            'country' => 'Italy',
                            'all_data' => serialize($event),
                            'source' => 'app.ticketmaster.com/discovery/v2/events',
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()')
                        ]);

                        echo 'events ' . $j . "\n";
                    }
                } else {
                    break;
                }
                echo ++$requestCount . ' $reponse not empty' . "\n";
            }
        }
        */
