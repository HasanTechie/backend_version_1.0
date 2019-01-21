<?php

use Illuminate\Database\Seeder;

class testSeeder1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

//        for ($i = 1; $i <= 250000; $i++) {
//            if (DB::table('flights_data')->where('id', $i)->exists()) {
//                $results = DB::table('flights_data')->where('id', $i)->get();
//
//
//                foreach ($results as $instance) {
//
//                    DB::table('flights_test')->insert([
//                        'all_data' => gzcompress($instance->all_data),
//                        'source' => 'developer.laminardata.aero',
//                        'created_at' => DB::raw('now()'),
//                        'updated_at' => DB::raw('now()')
//                    ]);
//                }
//            }
//        }

        for ($i = 1; $i <= 250000; $i++) {
            rand(0,250000);
            if (DB::table('flights_data')->where('id', $i)->exists()) {
                $results = DB::table('flights_data')->where('id', $i)->get();

                foreach ($results as $instance) {
                    if (DB::table('flights')->where('iata_flight_number', unserialize($instance->all_data)->properties->iataFlightNumber )->exists()) {
                        echo 'yes ';
                    } else {
                        echo 'no ';
                    }
                }
            }
        }
    }
}
