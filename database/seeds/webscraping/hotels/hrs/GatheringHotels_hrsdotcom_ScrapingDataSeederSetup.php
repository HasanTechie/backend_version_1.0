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
        $hrs = new GatheringHotels_hrsdotcom_ScrapingDataSeederMain();

        $dataArray = [
            'start_date' => '2019-02-26',
            'end_date' => '2020-02-26',
            'city' => 'Paris',
            'city_id' => 49551,
            'country' => 'France',
        ];

        $hrs->mainRun($dataArray);
    }
}
