<?php

use Illuminate\Database\Seeder;

class testSeeder3 extends Seeder
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
//        for ($i = 1; $i <= 300000; $i++) {

            $results = DB::table('hotels_uncompressed')->select('uid')->get();
            if (!empty($results[0])) {
                foreach ($results as $instance) {
                    DB::table('hotels_uncompressed')->where('uid', $instance->uid)->update([
                        's_no' => ++$j,
                        ]);

                    echo 'hotels_uncompressed ' .'->' . $j . "\n";
                }
            }
//        }


    }
}
