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

                        if (unserialize($instance->all_data_detailed)->location == 'Berlin') {
                            $country = 'Germany';
                        }
                        if (unserialize($instance->all_data_detailed)->location == 'Amsterdam') {
                            $country = 'Netherlands';
                        }
                        if (unserialize($instance->all_data_detailed)->location == 'Barcelona') {
                            $country = 'Spain';
                        }
                        if (unserialize($instance->all_data_detailed)->location == 'Dubai') {
                            $country = 'United Arab Emirates';
                        }
                        if (unserialize($instance->all_data_detailed)->location == 'London') {
                            $country = 'United Kingdom';
                        }
                        if (unserialize($instance->all_data_detailed)->location == 'Paris') {
                            $country = 'France';
                        }
                        if (unserialize($instance->all_data_detailed)->location == 'Rome' || unserialize($instance->all_data_detailed)->location == 'Tuscany') {
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
                            'name' => unserialize($instance->all_data_detailed)->name,
                            'address' => unserialize($instance->all_data_detailed)->address,
                            'city' => unserialize($instance->all_data_detailed)->location,
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
