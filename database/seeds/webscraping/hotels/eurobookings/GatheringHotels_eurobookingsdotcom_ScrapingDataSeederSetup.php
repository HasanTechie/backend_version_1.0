<?php

class GatheringHotels_eurobookingsdotcom_ScrapingDataSeederSetup extends GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $euroBooking = new GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain();



        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Hamburg',
            'city_id' => 1427,
            'country_code' => 'DE',
            'total_results' => 341,
        ];


        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Berlin',
            'city_id' => 536,
            'country_code' => 'DE',
            'total_results' => 723,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Frankfurt',
            'city_id' => 1246,
            'country_code' => 'DE',
            'total_results' => 234,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Munich',
            'city_id' => 2452,
            'country_code' => 'DE',
            'total_results' => 364,
        ];



        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Cologne',
            'city_id' => 821,
            'country_code' => 'DE',
            'total_results' => 264,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Paris',
            'city_id' => 2734,
            'country_code' => 'FR',
            'total_results' => 2389,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'London',
            'city_id' => 2114,
            'country_code' => 'UK',
            'total_results' => 5628,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Barcelona',
            'city_id' => 513,
            'country_code' => 'ES',
            'total_results' => 1411,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Prague',
            'city_id' => 2872,
            'country_code' => 'CZ',
            'total_results' => 953,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Amsterdam',
            'city_id' => 378,
            'country_code' => 'NL',
            'total_results' => 606,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Brussels',
            'city_id' => 690,
            'country_code' => 'BE',
            'total_results' => 357,
        ];

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-05',
            'end_date' => '2019-03-05',
            'city' => 'Rome',
            'city_id' => 3023,
            'country_code' => 'IT',
            'total_results' => 5135,
        ];

        foreach ($dataArray as $instance) {
            $euroBooking->mainRun($instance);

        }
    }
}
