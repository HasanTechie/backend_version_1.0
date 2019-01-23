<?php

use Illuminate\Database\Seeder;

class UncompressingDataSeeder extends Seeder
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
            if (DB::table('hotels')->where('s_no', $i)->exists()) {

                $results = DB::table('hotels')->where('s_no', $i)->get();

                foreach ($results as $instance) {

                    DB::table('hotels_uncompressed')->insert([
                        'uid' => $instance->uid,
                        's_no' => $instance->s_no,
                        'name' => isset($instance->name) ? $instance->name : null,
                        'address' => isset($instance->address) ? $instance->address : null,
                        'city' => isset($instance->city) ? $instance->city : null,
                        'country' => isset($instance->country) ? $instance->country : null,
                        'phone' => isset($instance->phone) ? $instance->phone : null,
                        'website' => isset($instance->website) ? $instance->website : null,
                        'latitude' => isset($instance->latitude) ? $instance->latitude : null,
                        'longitude' => isset($instance->longitude) ? $instance->longitude : null,
                        'all_data' => gzuncompress($instance->all_data),
                        'source' => 'tour-pedia.org/api/',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo '1 '. $j . "\n";
                }
            }
        }
    }
}
