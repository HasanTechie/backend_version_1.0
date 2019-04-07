<?php

use Illuminate\Database\Seeder;

class Rooms_hrs_SeederSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $dA['currency'] = 'EUR';
        $dA['start_date'] = '2019-04-14';
        $dA['end_date'] = '2019-04-14';
        $dA['adults'] = [1, 2];

        $hotels = DB::table('hotels_hrs')->inRandomOrder()->get();

        foreach ($hotels as $hotel) {
            $roomClass = new Rooms_hrs_Seeder();

            $roomClass->mainRun($hotel, $dA);
        }
    }
}
