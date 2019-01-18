<?php

use Illuminate\Database\Seeder;

class MergingDataGoogleMapsAPISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 1; $i <= 1000; $i++) {
            if (DB::table('hotels_1')->where('id', $i)->exists()) {
                $results = DB::table('hotels_1')->where('id', $i)->get();


                foreach ($results as $instance) {

                    DB::table('hotels')->insert([
                        'name' => $instance->name,
                        'address' => $instance->address,
                        'city' => $instance->city,
                        'country' => $instance->country,
                        'phone' => $instance->phone,
                        'website' => isset($instance->website) ? $instance->website : null,
                        'latitude' => isset(unserialize($instance->geometry)->location->lat) ? unserialize($instance->geometry)->location->lat : null,
                        'longitude' => isset(unserialize($instance->geometry)->location->lng) ? unserialize($instance->geometry)->location->lng : null,
                        'all_data' => gzcompress($instance->all_data),
                        'source' => 'maps.googleapis.com',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                }
            }
        }
    }
}
