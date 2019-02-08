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


        $results = DB::table('rooms')->get();

        foreach ($results as $instance) {

            DB::table('rooms')->where('uid', $instance->uid)->update([
                's_no' => ++$j,
            ]);
            echo 'rooms ' . $j . "\n";
        }


    }
}
