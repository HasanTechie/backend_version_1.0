<?php

class GatheringHotels_eurobookingsdotcom_ScrapingDataSeederSetup extends GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = DB::table('hotels_basic_data_for_gathering')->where('city','=','Prague')->get();

        $euroBooking = new GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder();
        for ($k = -1; $k <= 5; $k++) {
            foreach ($cities as $instance) {
                $instance = (array)$instance;
                $instance['start_date'] = '2019-04-10';
                $instance['end_date'] = '2019-04-10';
                $instance['k'] = $k;
                $euroBooking->mainRun($instance);
            }
        }
    }
}
