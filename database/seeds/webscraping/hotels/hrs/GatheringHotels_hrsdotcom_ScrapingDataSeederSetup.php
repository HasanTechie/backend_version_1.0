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

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-10',
            'end_date' => '2020-03-10',
            'city' => 'Paris',
            'city_id' => 49551,
            'country' => 'France',
        ];
        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-10',
            'end_date' => '2020-03-10',
            'city' => 'Frankfurt-am-main',
            'city_id' => 91191,
            'country' => 'Germany',
        ];
        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-10',
            'end_date' => '2020-03-10',
            'city' => 'Berlin',
            'city_id' => 55133,
            'country' => 'Germany',
        ];
        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-10',
            'end_date' => '2020-03-10',
            'city' => 'Rome',
            'city_id' => 54084,
            'country' => 'Italy',
        ];

        foreach ($dataArray as $instance) {

            $hrs->mainRun($instance);
        }
    }
}
