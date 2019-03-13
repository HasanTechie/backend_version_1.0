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

//        $dataArray [] = [
//            'currency' => 'EUR',
//            'start_date' => '2019-03-20',
//            'end_date' => '2020-03-20',
//            'city' => 'Rome',
//            'city_id' => 54084,
//            'country_code' => 'IT',
//        ];
//        $dataArray [] = [
//            'currency' => 'EUR',
//            'start_date' => '2019-03-14',
//            'end_date' => '2020-03-14',
//            'city' => 'Paris',
//            'city_id' => 49551,
//            'country_code' => 'FR',
//        ];
//        $dataArray [] = [
//            'currency' => 'EUR',
//            'start_date' => '2019-03-14',
//            'end_date' => '2020-03-14',
//            'city' => 'Frankfurt-am-main',
//            'city_id' => 91191,
//            'country_code' => 'DE',
//        ];
        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-14',
            'end_date' => '2020-03-14',
            'city' => 'Berlin',
            'city_id' => 55133,
            'country_code' => 'DE',
        ];

        foreach ($dataArray as $instance) {

            $hrs->mainRun($instance);
        }
    }
}
