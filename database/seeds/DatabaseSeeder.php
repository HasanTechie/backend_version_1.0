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
//        $this->call('PlacesDetailsReviewsSeeder');
//        $this->call('MergeHotelPlacesDataSeeder');
//        $this->call('CountriesDataFromHotelBedsSeeder');
//        $this->call('HotelDataFromHotelBedsAPISeeder');
//        $this->call('UpdateFlightsData1');
//        $this->call('MergingDataGoogleMapsAPISeeder');
//        $this->call('MergingDataHotelBedsAPISeeder');
//        $this->call('SingaporeHotelDataSeeder');
//        $this->call('MergingDataTourPediaAPISeeder');
//        $this->call('MergingDataTourPediaAPIplacesSeeder');
//        $this->call('testSeeder1');
//        $this->call('MergingDataLaminarAPISeeder');
        $this->call('GatheringAirfranceKLMAPIFlightFaresSeeder');
    }
}
