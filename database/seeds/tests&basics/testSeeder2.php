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


        $results = DB::table('cities')->get();

        foreach ($results as $instance) {

            DB::table('cities')->where('uid', $instance->uid)->update([
                's_no' => ++$j,
            ]);
            echo 's_no. ' . $j . "\n";
        }


    }
}
