<?php

use Illuminate\Database\Seeder;

class GatheringHotels_hrsdotcom_ScrapingDataSeederSetupSelected extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $hrs = new GatheringHotels_hrsdotcom_ScrapingDataSeederMainSelected();

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-22',
            'end_date' => '2020-03-22',
            'city' => 'Rome',
            'city_id' => 54084,
            'country_code' => 'IT',
        ];
        /*$dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-22',
            'end_date' => '2020-03-22',
            'city' => 'Paris',
            'city_id' => 49551,
            'country_code' => 'FR',
        ];
        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-22',
            'end_date' => '2020-03-22',
            'city' => 'Frankfurt-am-main',
            'city_id' => 91191,
            'country_code' => 'DE',
        ];
        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-22',
            'end_date' => '2020-03-22',
            'city' => 'Berlin',
            'city_id' => 55133,
            'country_code' => 'DE',
        ];*/

        foreach ($dataArray as $instance) {

            $hrs->run($instance);
        }
    }
}
