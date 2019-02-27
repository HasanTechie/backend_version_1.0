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

//        $dataArray = [
//            'adults' => 2,
//            'currency' => 'EUR',
////            'start_date' => '2019-02-26',
//            'start_date' => '2019-05-26',
//            'end_date' => '2020-02-26',
//            'city' => 'Frankfurt',
//            'city_id' => 1246,
//            'country' => 'Germany',
////            'total_results' => 234,
//        ];
//        $dataArray = [
//            'adults' => 2,
//            'currency' => 'EUR',
//            'start_date' => '2019-02-26',
//            'end_date' => '2020-02-26',
//            'city' => 'Munich',
//            'city_id' => 2452,
//            'country' => 'Germany',
//            'total_results' => 361,
//        ];
        $dataArray = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-02-28',
            'end_date' => '2020-02-26',
            'city' => 'Rome',
            'city_id' => 3023,
            'country' => 'Italy',
            'total_results' => 2000,
        ];
//        $dataArray = [
//            'adults' => 2,
//            'currency' => 'EUR',
//            'start_date' => '2019-02-25',
//            'end_date' => '2020-02-25',
//            'city' => 'Berlin',
//            'city_id' => 536,
//            'country' => 'Germany',
//            'total_results' => 717,
//        ];

        $euroBooking->mainRun($dataArray);
    }
}
