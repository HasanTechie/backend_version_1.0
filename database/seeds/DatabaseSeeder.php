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
        $this->call('MergingDataGoogleMapsAPISeeder'); // neet to run at 136
        $this->call('MergingDataHotelBedsAPISeeder');
        $this->call('MergingDataTourPediaAPISeeder');
        $this->call('SingaporeHotelDataSeeder');
//        $this->call('MergingDataTourPediaAPIplacesSeeder'); //running at 208
//        $this->call('MergingDataLaminarAPISeeder');
//        $this->call('GatheringAirfranceKLMAPIFlightFaresSeeder');
//        $this->call('GatheringAirfranceKLMAPIFlightFaresSeeder2');
//        $this->call('CreateUniqueIdSeeder'); //running
//        $this->call('UncompressingDataSeeder'); //running
//        $this->call('UncompressingDataSeeder2'); //running
//        $this->call('testSeeder1');
//        $this->call('testSeeder2');
//        $this->call('testSeeder3');
//        $this->call('GatheringAirfranceKLMAPIReferenceDataSeeder');

    }
}
