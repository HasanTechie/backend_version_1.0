<?php

use Illuminate\Database\Seeder;

class GatheringHotels_hrsdotcom_ScrapingDataSeederSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = DB::table('hotels_basic_data_for_gathering')->where('source','=','hrs.com')->inRandomOrder()->get();

        $hrs = new GatheringHotels_hrsdotcom_Hotels_ScrapingDataSeeder();
        foreach ($cities as $instance) {
            $instance = (array)$instance;
            $instance['start_date'] = '2019-04-10';
            $instance['end_date'] = '2019-04-10';
            $hrs->mainRun($instance);
        }
    }
}
