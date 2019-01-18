<?php

use Illuminate\Database\Seeder;

class MergingDataHotelBedsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ini_set('memory_limit', -1);

        for ($i = 1; $i <= 200000; $i++) {
            if (DB::table('hotel_beds')->where('id', $i)->exists()) {
                $results = DB::table('hotel_beds')->where('id', $i)->get();

                foreach ($results as $instance) {

                    if (isset(unserialize($instance->all_data)->phones)) {


                        foreach (unserialize($instance->all_data)->phones as $componentInstance) {
                            if (!is_array($componentInstance)) {
                                if ($componentInstance->phoneType == 'PHONEHOTEL') {
                                    $phone = $componentInstance->phoneNumber;
                                } else {
                                    if ($componentInstance->phoneType == 'PHONEBOOKING') {
                                        $phone = $componentInstance->phoneNumber;
                                    }
                                }
//
                            }
                        }
                    }

                    $country = DB::table('countries')->where('country_code', '=', $instance->country_code)->get();

                    DB::table('hotels')->insert([
                        'name' => $instance->name,
                        'address' => $instance->address,
                        'city' => $instance->city,
                        'country' => $country[0]->name,
                        'phone' => isset($phone) ? $phone : null,
                        'website' => isset($instance->website) ? $instance->website : null,
                        'latitude' => isset(unserialize($instance->all_data)->coordinates->latitude) ? unserialize($instance->all_data)->coordinates->latitude : null,
                        'longitude' => isset(unserialize($instance->all_data)->coordinates->longitude) ? unserialize($instance->all_data)->coordinates->longitude : null,
                        'all_data' => gzcompress($instance->all_data),
                        'source' => 'developer.hotelbeds.com',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                }
            }
        }


    }
}
