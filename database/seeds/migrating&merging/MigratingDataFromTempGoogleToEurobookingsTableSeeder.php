<?php

use Illuminate\Database\Seeder;

class MigratingDataFromTempGoogleToEurobookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $hotels = DB::table('hotels_eurobookings')
            ->whereNull('total_number_of_ratings_on_google')
            ->get();


        foreach ($hotels as $hotel) {

            $googleAll = DB::table('temp_google_data')->get();

            foreach ($googleAll as $instance) {

                $googleData = unserialize($instance->all_data_google);


                $array['elat1'] = $hotel->latitude;
                $array['lat1'] = round($hotel->latitude, 3);
                $array['glat2'] = $googleData->geometry->location->lat;
                $array['lat2'] = round($googleData->geometry->location->lat, 3);
                $array['elng1'] = $hotel->longitude;
                $array['lng1'] = round($hotel->longitude, 3);
                $array['glng2'] = $googleData->geometry->location->lng;
                $array['lng2'] = round($googleData->geometry->location->lng, 3);

                if (($array['lat1'] == $array['lat2']) && ($array['lng1'] == $array['lng2'])) {

                    $results2 = DB::table('hotels_eurobookings')->select(DB::raw('uid,name,address, ROUND(latitude,3) as lati ,latitude, ROUND(longitude,3) as longi,longitude'))->havingRaw(
                        'lati=' . round($hotel->latitude, 3) . ' AND longi=' . round($hotel->longitude, 3) . ' '
                    )->get();

//                    $result3 = DB::table('hotels_eurobookings')->where('name', $googleData->name)->whereNull('total_number_of_ratings_on_google')->get();
//
//                    if ($result3->count() == 1) {
//
////                        dd($result3);
//                        if ($result3[0]->name == $googleData->name) {
//
//                            $data = DB::table('hotels_eurobookings')
//                                ->where('uid', $result3[0]->uid)
//                                ->update([
//                                    'total_number_of_ratings_on_google' => $googleData->user_ratings_total,
//                                    'ratings_on_google' => $googleData->rating,
//                                    'all_data_google' => serialize($googleData),
//                                ]);
//
//                            if ($data == 1) {
//                                echo $result3[0]->name . ' ' . $googleData->name . "\n";
//                            }
//                        }
//                    }

                    if ($results2->count() == 1) {

                        $data = DB::table('hotels_eurobookings')
                            ->where('uid', $results2[0]->uid)
                            ->update([
                                'total_number_of_ratings_on_google' => $googleData->user_ratings_total,
                                'ratings_on_google' => $googleData->rating,
                                'all_data_google' => serialize($googleData),
                            ]);

                        if ($data == 1) {
                            echo $hotel->uid . ' h:' . $hotel->name . ' g:' . $googleData->name . "\n";
                        }
                    }
                }
            }
        }
    }
}
