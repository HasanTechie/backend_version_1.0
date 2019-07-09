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
        $dA['start_date'] = date("Y-m-d", strtotime("+10 day"));
        $dA['end_date'] = date("Y-m-d", strtotime("+120 day"));
        $dA['adults'] = [1, 2];

        $hotels = DB::table('hotels_hrs')->select('id', 'hrs_id', 'city')->whereIn('city', ['Rome', 'Berlin'])->get();

        foreach ($hotels as $hotel) {

            $roomClass = new Rooms_hrs_Seeder();

            $roomClass->run($hotel, $dA);
        }
    }
}
