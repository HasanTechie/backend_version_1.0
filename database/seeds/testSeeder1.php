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

//        for ($i = 1; $i <= 250000; $i++) {
//            $i = rand(0,250000);
//            if (DB::table('flights_data')->where('id', $i)->exists()) {
//                $results = DB::table('flights_data')->where('id', $i)->get();
//
//                foreach ($results as $instance) {
//
//                    if (DB::table('flights')->where('flight_id', unserialize($instance->all_data)->id )->exists()) {
//                        echo 'yes ' . unserialize($instance->all_data)->id . '  ' .  $i .  "\n ";
//                    } else {
//                        echo 'no ' . unserialize($instance->all_data)->id . '  ' .  $i . "\n ";
//                    }
//                }
//            }
//        }


        for ($i = 1; $i <= 600000; $i++) {
            if (DB::table('hotels')->where('id', $i)->exists()) {

                $results = DB::table('hotels')->where('id', 430422)->get();

                foreach ($results as $instance) {

                    dd(unserialize(gzuncompress($instance->all_data)));


                    if (!empty(unserialize($instance->all_data_detailed)->phone_number)) {
                        $phone = unserialize($instance->all_data_detailed)->phone_number;
                    } else {
                        if (!empty(unserialize($instance->all_data_detailed)->international_phone_number)) {
                            $phone = unserialize($instance->all_data_detailed)->international_phone_number;
                        }
                    }

                    DB::table('hotels')->insert([
                        'name' => isset(unserialize($instance->all_data_detailed)->name) ? unserialize($instance->all_data_detailed)->name : null,
                        'address' => isset(unserialize($instance->all_data_detailed)->address) ? unserialize($instance->all_data_detailed)->address : null,
                        'city' => isset($instance->location) ? $instance->location : null,
                        'country' => $country,
                        'phone' => isset($phone) ? $phone : null,
                        'website' => isset(unserialize($instance->all_data_detailed)->website) ? unserialize($instance->all_data_detailed)->website : null,
                        'latitude' => isset(unserialize($instance->all_data_detailed)->lat) ? unserialize($instance->all_data_detailed)->lat : null,
                        'longitude' => isset(unserialize($instance->all_data_detailed)->lng) ? unserialize($instance->all_data_detailed)->lng : null,
                        'all_data' => gzcompress($instance->all_data),
                        'source' => 'tour-pedia.org/api/',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                }
            }
        }


    }
}
