<?php

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
