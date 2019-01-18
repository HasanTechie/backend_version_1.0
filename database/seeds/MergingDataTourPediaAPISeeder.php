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
        for ($i = 1; $i <= 600000; $i++) {
            if (DB::table('places')->where('id', $i)->exists()) {

                $results = DB::table('places')->where('id', $i)->get();

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
                            'city' =>  isset(unserialize($instance->all_data_detailed)->location) ? unserialize($instance->all_data_detailed)->location : null,
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
}
