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
        $cities = DB::table('hotels_basic_data_for_gathering')->get();

        $euroBooking = new GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder();
        for ($k = -1; $k <= 5; $k++) {
            foreach ($cities as $instance) {
                $euroBooking->mainRun((array)$instance, $k);
            }
        }
    }
}
