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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
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
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-04-04',
            'end_date' => '2019-04-04',
            'city' => 'Hamburg',
            'city_id' => 1427,
            'country_code' => 'DE',
            'total_results' => 341,
            'source' => 'eurobookings.com'
        ];

        foreach ($dataArray as $instance) {
            DB::table('hotels_basic_data_for_gathering')->insert([
                'uid' => uniqid(),
                's_no' => 1,
                'adults' => $instance['adults'],
                'currency' => $instance['currency'],
                'city' => $instance['city'],
                'city_id' => $instance['city_id'],
                'country_code' => $instance['country_code'],
                'total_results' => $instance['total_results'],
                'source' => $instance['source'],
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }
    }
}
