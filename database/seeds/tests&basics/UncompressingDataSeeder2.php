<?php

use Illuminate\Database\Seeder;

class UncompressingDataSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $j = 0;
        for ($i = 1; $i <= 600000; $i++) {


            $results = DB::table('flights_afklm')->where('s_no', $i)->get();

            if (!empty($results[0])) {

                foreach ($results as $instance) {

                    DB::table('flights_afklm_uncompressed')->insert([
                        'uid' => isset($instance->uid) ? $instance->uid : null,
                        's_no' => isset($instance->s_no) ? $instance->s_no : null,
                        'airline_code' => isset($instance->airline_code) ? $instance->airline_code : null,
                        'origin_name' => isset($instance->origin_name) ? $instance->origin_name : null,
                        'origin_iata' => isset($instance->origin_iata) ? $instance->origin_iata : null,
                        'destination_name' => isset($instance->destination_name) ? $instance->uid : null,
                        'destination_iata' => isset($instance->destination_iata) ? $instance->destination_iata : null,
                        'flight_date' => isset($instance->flight_date) ? $instance->flight_date : null,
                        'all_data' => serialize(isset($instance->all_data) ? $instance->all_data : null),
                        'source' => 'api.klm.com/opendata/flightoffers/available-offers',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo 'flights_afklm -> flights_afklm_uncompressed ' . $i . "\n";
                }
            }
        }
    }
}
