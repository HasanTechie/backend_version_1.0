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
            array("22597326eeaf6f8ba8db0563ff8d0edc", "hasanabbax"),
            array("4f0a5b53fe164cb74f2f7d055193c806", "maxbiocca"),
        );

        $k = 0;

        $db = new FirestoreClient([
//            'keyFilePath' => __DIR__ . '/keys/solidps-backend-data-firebase-adminsdk-8d9sv-9bb2dbfcb0.json'
            'keyFilePath' => __DIR__ . '/keys/solidps-frontend-firebase-adminsdk-6o2qh-81d5c5fe40.json'
        ]);


        $hotels = DB::table('hotels')->where('website', '=', 'hotelportamaggiore.it')->orWhere('website', '=', 'novecentohotel.it')->get();


        foreach ($hotels as $hotel) {

            $properties = $db
                ->collection('properties')//hotels
                ->document($hotel->uid);

            //forbidden by max
//            $properties->set([
//                'name' => $hotel->name,
//                'address' => $hotel->address,
//                'city' => $hotel->city,
//                'country' => $hotel->country,
//                'phone' => $hotel->phone,
//                'website' => $hotel->website,
//            ]);


            if ($hotel->uid == '5c62bce9f062b') {
                $properties->set([
                    'name' => '4-star Hotel Berlin',
                    'city' => 'Berlin',
                    'country' => 'Germany',
                ]);

                $dates = DB::table('rooms_prices_hotel_novecento')->select('check_in_date')->distinct('check_in_date')->limit(10)->orderBy('check_in_date')->get();

            }

            if ($hotel->uid == '5c67dc940dec1') {
                $properties->set([
                    'name' => '3-star Hotel Rome',
                    'city' => 'Rome',
                    'country' => 'Italy',
                ]);
                $dates = DB::table('rooms_prices_vertical_booking')->select('check_in_date')->distinct('check_in_date')->where('hotel_website', '=', 'hotelportamaggiore.it')->limit(10)->orderBy('check_in_date')->get();
            }

//
            foreach ($dates as $date) {

                $calendar = $properties->collection('calendar')//dates
                ->document($date->check_in_date);


                if ($hotel->uid == '5c62bce9f062b') {
                    $events = DB::table('events')->where([
                        ['event_date', '=', $date->check_in_date],
                        ['city', '=', 'Berlin']
                    ])->get();
                    $url = "https://api.openweathermap.org/data/2.5/forecast?id=2950159&appid=" . $apiArray[$k][0]; //Berlin
                    $rooms = DB::table('rooms_prices_hotel_novecento')->where('check_in_date', '=', $date->check_in_date)->get();
                }

                if ($hotel->uid == '5c67dc940dec1') {
                    $events = DB::table('events')->where([
                        ['event_date', '=', $date->check_in_date],
                        ['city', '=', 'Rome']
                    ])->get();
                    $url = "https://api.openweathermap.org/data/2.5/forecast?id=6691831&appid=" . $apiArray[$k][0]; //Rome
                    $rooms = DB::table('rooms_prices_vertical_booking')->where([
                        ['check_in_date', '=', $date->check_in_date],
                        ['hotel_website', '=', 'hotelportamaggiore.it']
                    ])->get();
                }

                $eventArray = [];
                $i = 0;
                foreach ($events as $event) {
                    $eventArray[$i++] = array(
                        'event_price_min' => $event->standard_price_min,
                        'event_price_max' => $event->standard_price_max,
                        'event_name' => $event->name,
                        'event_venue_name' => $event->venue_name,
                        'event_date' => $event->event_date,
                        'event_city' => $event->city,
                        'event_url' => $event->url,
                    );
                }

                $client = new Client();

                $res = $client->request('GET', $url);
                $response = json_decode($res->getBody());
                $weather = [];
                foreach ($response->list as $instance) {

                    $dateTime = $instance->dt_txt;
                    $dateArray = explode(" ", $dateTime);
                    $weatherTime = $dateArray[1];
                    $weatherDate = $dateArray[0];

                    if ($weatherTime == '12:00:00' && $weatherDate == $date->check_in_date) {
                        $weather['avg_temp_in_celsius'] = round(($instance->main->temp - 273.15));
                        $weather['condition'] = $instance->weather[0]->description;
                    }
                }

                $d = date("d", strtotime($date->check_in_date));
                $m = date("m", strtotime($date->check_in_date));
                $y = date("Y", strtotime($date->check_in_date));
                $newWeather = '';
                if (!empty($weather['condition'])) {

                    $newWeather = str_replace(' ', '-', $weather['condition']);
                    $newWeather = 'weather-' . $newWeather;
                }

                $eventIndicator = [];
                if (count($eventArray) > 0) {
                    $eventIndicator [] = 'very-busy';
                }
                if (!empty($newWeather)) {
                    $eventIndicator [] = $newWeather;
                }

//                echo $hotel->uid . ' ' . $date->check_in_date . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n";

                $i = 0;
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
                                ->document(strtolower(str_replace(array(' ', ',', '/'), '', $room->room)));

                            $assets->set([
                                'name' => $room->room,
                                'room_description' => $room->room_description,
                            ]);


                            $options = $assets
                                ->collection('options')//options
                                ->document($room->uid);

                            $realPrice = (double)trim(str_replace(',', '.', str_replace('EUR', '', $room->display_price)));
                            $competitorPrice = (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_now)));
                            $suggestedPrice = (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_should)));

                            $marketValueOffset = ((($suggestedPrice - $realPrice) / $realPrice) * 100);
                            $marketValueOffsetArray [] = $marketValueOffset;
                            $options =
                                $options->set([
                                    'real_price' => $realPrice,
                                    'competitor_price' => $competitorPrice,
                                    'suggested_price' => $suggestedPrice,
                                    'market_value_offset_for_room' => round($marketValueOffset, 2),
                                    'hint' => $competitor->action,
                                    'name' => 'Normal'
                                ]);


                            $assets2 = $properties
                                ->collection('assets')//rooms
                                ->document(strtolower(str_replace(array(' ', ',', '/'), '', $room->room)));

                            $analytics2 = $assets2
                                ->collection('analytics')//dates
                                ->document($date->check_in_date);

                            $analytics2->set([
                                'real_price' => $realPrice,
                                'competitor_price' => $competitorPrice,
                                'suggested_price' => $suggestedPrice,
                                'date' => Carbon\Carbon::createFromDate($y, $m, $d),
                            ]);

                            $allRealPrice[] = $realPrice;
                            $allCompetitorPrice[] = $competitorPrice;
                            $allSuggestedPrice[] = $suggestedPrice;
                        }
                    }

                    if ($hotel->uid == '5c67dc940dec1') {

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
                                $i++;
                                $assets = $calendar
                                    ->collection('assets')//rooms
                                    ->document(strtolower(str_replace(array(' ', ',', '/'), '', $room->room)));

                                $assets->set([
                                    'name' => $room->room,
                                    'room_description' => $room->room_description,
                                    'room_capacity' => $room->number_of_adults_in_room_request . ' number of adults'
                                ]);


                                $options = $assets
                                    ->collection('options')//options
                                    ->document($room->uid);


                                $realPrice = (double)trim(str_replace(',', '.', str_replace('EUR', '', $room->display_price)));
                                $competitorPrice = (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_now)));
                                $suggestedPrice = (double)trim(str_replace(',', '.', str_replace('€', '', $competitor->prices_should)));

                                $marketValueOffset = (($suggestedPrice - $realPrice) / $realPrice) * 100;
                                $marketValueOffsetArray [] = $marketValueOffset;
                                $options =
                                    $options->set([
                                        'real_price' => $realPrice,
                                        'competitor_price' => $competitorPrice,
                                        'suggested_price' => $suggestedPrice,
                                        'market_value_offset_for_room' => round($marketValueOffset, 2),
                                        'hint' => $competitor->action,
                                        'name' => 'Normal'
                                    ]);


                                $assets2 = $properties
                                    ->collection('assets')//rooms
                                    ->document(strtolower(str_replace(array(' ', ',', '/'), '', $room->room)));

                                $assets2->set([
                                    'name' => $room->room
                                ]);

                                $analytics2 = $assets2
                                    ->collection('analytics')//dates
                                    ->document($date->check_in_date);

//                                if ($i == 2) {
//                                    dd($room);
//                                }
                                $analytics2->set([
                                    'real_price' => $realPrice,
                                    'competitor_price' => $competitorPrice,
                                    'suggested_price' => $suggestedPrice,
                                    'date' => Carbon\Carbon::createFromDate($y, $m, $d),
                                ]);

                                $allRealPrice[] = $realPrice;
                                $allCompetitorPrice[] = $competitorPrice;
                                $allSuggestedPrice[] = $suggestedPrice;


                                //properties/{property_id}/assets/{asset_id}/analytics{date_id, for example 2015-02-20}
                            }
                        }
                    }
                }

                $assets3 = $properties
                    ->collection('assets')//rooms
                    ->document('all');

                $analytics3 = $assets3
                    ->collection('analytics')//dates
                    ->document($date->check_in_date);

                $allRealPrice = array_filter($allRealPrice);
                $averageAllRealPrice = array_sum($allRealPrice) / count($allRealPrice);

                $analytics3->set([
                    'avg_real_price' => $averageAllRealPrice,
                    'date' => Carbon\Carbon::createFromDate($y, $m, $d),
                ]);

                $calendar->set([
                    'date' => Carbon\Carbon::createFromDate($y, $m, $d),
                    'weather' => ((count($weather) > 0) ? $weather : null),
                    'events' => (count($eventArray) > 0) ? $eventArray : null,
                    'hints' => ((count($eventIndicator) > 0) ? $eventIndicator : null),
                    'market_suggestions' => [
                        'max_marketvalue_offset' => (count($marketValueOffsetArray) > 0) ? round(max($marketValueOffsetArray), 2) : null,
                        'min_marketvalue_offset' => (count($marketValueOffsetArray) > 0) ? round(min($marketValueOffsetArray), 2) : null,
                    ]
                ]);
                $marketValueOffsetArray = [];
            }

        }

    }

    /*
room competitor = 34.04
room real_price = 64
room suggested_price = 32.42

marketvalue_offset = ((suggested_price-real_price)/real_price)*100

max_marketvalue_offset = -99999999

for (room in calendarday.rooms) {
int marketvalue_offset = ((suggested_price-real_price)/real_price)*100

if (marketvalue_offset > max_marketvalue_offset) {
  max_marketvalue_offset = marketvalue_offset
}
}
     */
}


/*
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
*/

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
