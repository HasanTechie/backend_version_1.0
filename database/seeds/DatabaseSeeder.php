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
        $this->call('PlacesSeeder');
    }
}
