<?php

use Illuminate\Database\Seeder;

class testSeeder2 extends Seeder
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


        $results = DB::table('rooms_prices_vertical_booking')->get();

        foreach ($results as $instance) {

            DB::table('rooms_prices_vertical_booking')->where('uid', $instance->uid)->update([
                's_no' => ++$j,
            ]);
            echo 's_no. ' . $j . "\n";
        }


    }
}
