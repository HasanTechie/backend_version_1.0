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

            $google = DB::table('temp_google_data')->get();

            foreach ($google as $instance) {

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

                    echo 'if';
                } else {
                    print_r($array);
                    echo 'else';
                }
            }
        }
    }
}
