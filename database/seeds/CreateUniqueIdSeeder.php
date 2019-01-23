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
                    echo $j . ' ';
                }
            }
        }


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
                    echo $j . ' ';
                }
            }
        }
    }
}
