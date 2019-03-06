<?php

use Illuminate\Database\Seeder;

class GatheringHotels_eurobookingsdotcom_ScrapingDataSeederSetupSelected extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $euroBooking = new GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMainSelected();

        $dataArray[] = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-11',
            'end_date' => '2020-03-11',
            'city' => 'Rome',
            'city_id' => 3023,
            'country_code' => 'IT',
            'total_results' => 5135,
            'source' => 'eurobookings.com'
        ];

        foreach ($dataArray as $instance) {
            $euroBooking->mainRun($instance);

        }

    }
}
