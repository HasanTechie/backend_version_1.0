<?php

use Illuminate\Database\Seeder;

class CreateUniqueIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        $j=0;
        for ($i = 1; $i <= 400000; $i++) {
            $j++;
            if (DB::table('flights_1')->where('id', $i)->exists()) {

                $results = DB::table('flights_1')->where('id', $i)->get();

                foreach ($results as $instance) {

                    DB::table('flights')->insert([
                        'uid' => uniqid(),
                        's_no' => $j,
                        'flight_id' => $instance->flight_id,
                        'iata_flight_number' => $instance->iata_flight_number,
                        'flight_status' => $instance->flight_status,
                        'aircraft_code' => $instance->aircraft_code,
                        'aircraft_registration' => $instance->aircraft_registration,
                        'airline' => $instance->airline,
                        'arrival_airport_scheduled' => $instance->arrival_airport_scheduled,
                        'arrival_airport_initial' => $instance->arrival_airport_initial,
                        'arrival_runway_time_initial_date' => $instance->arrival_runway_time_initial_date,
                        'arrival_runway_time_initial_time' => $instance->arrival_runway_time_initial_time,
                        'arrival_runway_time_estimated_date' => $instance->arrival_runway_time_estimated_date,
                        'arrival_runway_time_estimated_time' => $instance->arrival_runway_time_estimated_time,
                        'callsign' => $instance->callsign,
                        'departure_airport_scheduled' => $instance->departure_airport_scheduled,
                        'departure_airport_initial' => $instance->departure_airport_initial,
                        'departure_runway_time_initial_date' => $instance->departure_runway_time_initial_date,
                        'departure_runway_time_initial_time' => $instance->departure_runway_time_initial_time,
                        'departure_runway_time_estimated_date' => $instance->departure_runway_time_estimated_date,
                        'departure_runway_time_estimated_time' => $instance->departure_runway_time_estimated_time,
                        'timestamp_processed_date' => $instance->timestamp_processed_date,
                        'timestamp_processed_time' => $instance->timestamp_processed_time,
                        'source' => 'developer.laminardata.aero',
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo 'flights '. $j . "\n";
                }
            }
        }



        /*
        $j=0;
        for ($i = 1; $i <= 600000; $i++) {
            $j++;
            if (DB::table('hotels_1')->where('id', $i)->exists()) {

                $results = DB::table('hotels_1')->where('id', $i)->get();

                foreach ($results as $instance) {

                    DB::table('hotels')->insert([
                        'uid' => uniqid(),
                        's_no' => $j,
                        'name' => isset($instance->name) ? $instance->name : null,
                        'address' => isset($instance->address) ? $instance->address : null,
                        'city' => isset($instance->city) ? $instance->city : null,
                        'country' => isset($instance->country) ? $instance->country : null,
                        'phone' => isset($instance->phone) ? $instance->phone : null,
                        'website' => isset($instance->website) ? $instance->website : null,
                        'latitude' => isset($instance->latitude) ? $instance->latitude : null,
                        'longitude' => isset($instance->longitude) ? $instance->longitude : null,
                        'all_data' => $instance->all_data,
                        'source' => 'tour-pedia.org/api/',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo '1 '. $j . "\n";
                }
            }
        }
        */


        /*
        $j=0;
        for ($i = 1; $i <= 600000; $i++) {
            $j++;
            if (DB::table('places_1')->where('id', $i)->exists()) {

                $results = DB::table('places_1')->where('id', $i)->get();

                foreach ($results as $instance) {

                    DB::table('places')->insert([
                        'uid' => uniqid(),
                        's_no' => $j,
                        'place_id' => isset($instance->place_id) ? $instance->place_id : null,
                        'name' => isset($instance->name) ? $instance->name : null,
                        'address' => isset($instance->address) ? $instance->address : null,
                        'category' => isset($instance->category) ? $instance->category : null,
                        'city' => isset($instance->city) ? $instance->city : null,
                        'country' => isset($instance->country) ? $instance->country : null,
                        'phone' => isset($instance->phone) ? $instance->phone : null,
                        'website' => isset($instance->website) ? $instance->website : null,
                        'latitude' => isset($instance->latitude) ? $instance->latitude : null,
                        'longitude' => isset($instance->longitude) ? $instance->longitude : null,
                        'all_data' => $instance->all_data,
                        'all_data_detailed' => $instance->all_data_detailed,
                        'all_data_detailed_reviews' => $instance->all_data_detailed_reviews,
                        'source' => 'tour-pedia.org/api/',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo '2 '. $j . "\n";
                }
            }
        }
        */

    }
}
