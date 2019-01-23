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
        $j=0;
        for ($i = 1; $i <= 600000; $i++) {
            $j++;
            if (DB::table('places')->where('s_no', $i)->exists()) {

                $results = DB::table('places')->where('s_no', $i)->get();

                foreach ($results as $instance) {

                    DB::table('places_uncompressed')->insert([
                        'uid' => $instance->uid,
                        's_no' => $instance->s_no,
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
                        'all_data' => gzuncompress($instance->all_data),
                        'all_data_detailed' => gzuncompress($instance->all_data_detailed),
                        'all_data_detailed_reviews' => gzuncompress($instance->all_data_detailed_reviews),
                        'source' => 'tour-pedia.org/api/',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo '2 '. $j . "\n";
                }
            }
        }
    }
}
