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

        $hotels = DB::table('hotels')->where('website', '=', 'novecentohotel.it')->get();


        foreach ($hotels as $hotel) {

            $properties = $db
                ->collection('properties')//hotles
                ->document($hotel->uid);

//            $properties->set([
//                'name' => $hotel->name,
//                'address' => $hotel->address,
//                'city' => $hotel->city,
//                'country' => $hotel->country,
//                'phone' => $hotel->phone,
//                'website' => $hotel->website,
//            ]);

            $properties->set([
                'name' => 'Hotel 4-star',
                'city' => 'Berlin',
                'country' => 'Germany',
            ]);


            $dates = DB::table('rooms_prices_hotel_novecento')->select('check_in_date')->distinct('check_in_date')->get();

            foreach ($dates as $date) {

                $calendar = $properties->collection('calendar')//dates
                ->document(uniqid());


                $d = date("d", strtotime($date->check_in_date));
                $m = date("m", strtotime($date->check_in_date));
                $y = date("Y", strtotime($date->check_in_date));


                $calendar->set([
                    'date' => Carbon\Carbon::createFromDate($y, $m, $d)
                ]);


                $rooms = DB::table('rooms_prices_hotel_novecento')->where('check_in_date', '=', $date->check_in_date)->limit(100)->get();


                foreach ($rooms as $room) {

                    $assets = $calendar
                        ->collection('assets')//rooms
                        ->document($room->uid);


                    $assets->set([
                        'name' => $room->room,
                        'room_description' => $room->room_description
                    ]);

                    $options = $assets
                        ->collection('options')//options
                        ->document(uniqid());


                    $competitor = DB::table('rooms_prices_4_star_fake')->where([
                        ['date', '=', $d . '.' . $m . '.' . $y],
                        ['type', '=', $room->room],
                        ['sales_type', '=', 'Economy'],

                    ])->get();

                    if (count($competitor) > 0) {
                        $options =
                            $options->set([
                                'real_price' => (double)trim(str_replace(',', '.', str_replace('EUR', '', $room->display_price))),
                                'competitor_price' => (double)trim(str_replace(',', '.', str_replace('€', '', $competitor[0]->prices_now))),
                                'suggested_price' => (double)trim(str_replace(',', '.', str_replace('€', '', $competitor[0]->prices_should))),
                                'hint' => $competitor[0]->action,
                                'name' => 'Normal'
                            ]);
                    } else {
                        $options =
                            $options->set([
                                'real_price' => (double)trim(str_replace(',', '.', str_replace('EUR', '', $room->display_price))),
                            ]);
                    }
//
                }
            }
        }


//        $collectionReference = $firestore->collection('Users');

//        $documentReference = $collectionReference->document($userId);
//        $snapshot = $documentReference->snapshot();
//
//        echo "Hello " . $snapshot['firstName'];
    }
}
