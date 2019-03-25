<?php

use Illuminate\Database\Seeder;

class EnteringEurobookingsAndHrsBasicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Cologne',
            'city_id' => 821,
            'country_code' => 'DE',
            'total_results' => 264,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Prague',
            'city_id' => 2872,
            'country_code' => 'CZ',
            'total_results' => 953,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Rome',
            'city_id' => 3023,
            'country_code' => 'IT',
            'total_results' => 5135,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Berlin',
            'city_id' => 536,
            'country_code' => 'DE',
            'total_results' => 723,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'London',
            'city_id' => 2114,
            'country_code' => 'UK',
            'total_results' => 5628,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Brussels',
            'city_id' => 690,
            'country_code' => 'BE',
            'total_results' => 357,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Amsterdam',
            'city_id' => 378,
            'country_code' => 'NL',
            'total_results' => 606,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Barcelona',
            'city_id' => 513,
            'country_code' => 'ES',
            'total_results' => 1411,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Munich',
            'city_id' => 2452,
            'country_code' => 'DE',
            'total_results' => 364,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Paris',
            'city_id' => 2734,
            'country_code' => 'FR',
            'total_results' => 2389,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Frankfurt',
            'city_id' => 1246,
            'country_code' => 'DE',
            'total_results' => 234,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Hamburg',
            'city_id' => 1427,
            'country_code' => 'DE',
            'total_results' => 341,
            'source' => 'eurobookings.com'
        ];
        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Istanbul',
            'city_id' => 1639,
            'country_code' => 'TR',
            'total_results' => 2201,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Moscow',
            'city_id' => 2395,
            'country_code' => 'RU',
            'total_results' => 1432,
            'source' => 'eurobookings.com'
        ];

        $dataArray[] = [
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Vienna',
            'city_id' => 3704,
            'country_code' => 'AT',
            'total_results' => 700,
            'source' => 'eurobookings.com'
        ];
        //HRS

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Berlin',
            'city_id' => 55133,
            'country_code' => 'DE',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Hamburg',
            'city_id' => 107905,
            'country_code' => 'DE',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2020-03-28',
            'city' => 'Frankfurt-am-main',
            'city_id' => 91191,
            'country_code' => 'DE',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Munich',
            'city_id' => 70801,
            'country_code' => 'DE',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Cologne',
            'city_id' => 62901,
            'country_code' => 'DE',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2020-03-28',
            'city' => 'Paris',
            'city_id' => 49551,
            'country_code' => 'FR',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Rome',
            'city_id' => 54084,
            'country_code' => 'IT',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'London',
            'city_id' => 71302,
            'country_code' => 'UK',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Istanbul',
            'city_id' => 56905,
            'country_code' => 'TR',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Barcelona',
            'city_id' => 64430,
            'country_code' => 'ES',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Amsterdam',
            'city_id' => 229567,
            'country_code' => 'NL',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Prague',
            'city_id' => 49370,
            'country_code' => 'CZ',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Brussels',
            'city_id' => 148670,
            'country_code' => 'BE',
            'source' => 'hrs.com'
        ];

        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Moscow',
            'city_id' => 97830,
            'country_code' => 'RU',
            'source' => 'hrs.com'
        ];
        $dataArray [] = [
            'currency' => 'EUR',
            'start_date' => '2019-03-28',
            'end_date' => '2019-03-28',
            'city' => 'Vienna',
            'city_id' => 45883,
            'country_code' => 'AT',
            'source' => 'hrs.com'
        ];

//        $dataArray [] = [
//            'currency' => 'EUR',
//            'start_date' => '2019-03-28',
//            'end_date' => '2019-03-28',
//            'city' => 'null',
//            'city_id' => null,
//            'country_code' => 'null',
//            'source' => 'null'
//        ];

//        foreach ($dataArray as $instance) {
//            DB::table('hotels_basic_data_for_gathering')->insert([
//                'uid' => uniqid(),
//                's_no' => 1,
//                'currency' => $instance['currency'],
//                'city' => $instance['city'],
//                'city_id' => $instance['city_id'],
//                'country_code' => $instance['country_code'],
//                'total_results' => isset($instance['total_results']) ? $instance['total_results'] : null,
//                'source' => $instance['source'],
//                'created_at' => DB::raw('now()'),
//                'updated_at' => DB::raw('now()')
//            ]);
//        }
    }
}
