<?php

use Illuminate\Database\Seeder;

class MergingDataTourPediaAPISeeder extends Seeder
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


            $results = DB::table('places_test')->where('id', $i)->get();
            if (!empty($results[0])) {
                foreach ($results as $instance) {
                    if ((strcasecmp($instance->subCategory, 'hotel') == 0) || (stripos(strtolower($instance->name), 'hotel') !== false)) { // case insensitive comparisons

                        if ($instance->location == 'Berlin') {
                            $country = 'Germany';
                        }
                        if ($instance->location == 'Amsterdam') {
                            $country = 'Netherlands';
                        }
                        if ($instance->location == 'Barcelona') {
                            $country = 'Spain';
                        }
                        if ($instance->location == 'Dubai') {
                            $country = 'United Arab Emirates';
                        }
                        if ($instance->location == 'London') {
                            $country = 'United Kingdom';
                        }
                        if ($instance->location == 'Paris') {
                            $country = 'France';
                        }
                        if ($instance->location == 'Rome' || $instance->location == 'Tuscany') {
                            $country = 'Italy';
                        }


                        DB::table('hotels_uncompressed')->insert([
                            'uid' => uniqid(),
                            's_no' => ++$j,
                            'name' => isset($instance->name) ? $instance->name : null,
                            'address' => isset($instance->address) ? $instance->address : null,
                            'city' => isset($instance->location) ? $instance->location : null,
                            'country' => $country,
                            'phone' => isset($instance->phone_number) ? $instance->phone_number : null,
                            'website' => isset(unserialize($instance->all_data_detailed)->website) ? unserialize($instance->all_data_detailed)->website : null,
                            'latitude' => isset(unserialize($instance->all_data_detailed)->lat) ? unserialize($instance->all_data_detailed)->lat : null,
                            'longitude' => isset(unserialize($instance->all_data_detailed)->lng) ? unserialize($instance->all_data_detailed)->lng : null,
                            'all_data' => $instance->all_data_detailed,
                            'source' => 'tour-pedia.org/api/',
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()')
                        ]);

                        echo 'places_test->hotels_uncompressed ' . $j . "\n";
                    }
                }
            }
        }


    }
}
