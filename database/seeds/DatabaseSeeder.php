<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call('ScheduledFlightsSeeder');
//        $this->call('CompletedFlightsSeeder');
//        $this->call('AirborneFlightsSeeder');
//        $this->call('HotelDetailsSeeder');
//        $this->call('PlacesSeeder');
        $this->call('PlacesDetailsReviewsSeeder');
//        $this->call('SingaporeHotelDataSeeder');
//        $this->call('MergeHotelPlacesDataSeeder');
//        $this->call('CountriesDataFromHotelBedsSeeder');
//        $this->call('HotelDataFromHoteBedsAPISeeder');
    }
}
