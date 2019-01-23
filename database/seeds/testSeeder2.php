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
        for ($i = 1; $i <= 100000; $i++) {
            if (DB::table('airports')->where('s_no', $i)->exists()) {

                $results = DB::table('airports')->where('s_no', $i)->get();

                foreach ($results as $instance) {

                    DB::table('airports')->where('s_no', $instance->s_no)->update([
                        's_no' => ++$j,
                    ]);
                    echo 'airports ' . $j . "\n";
                }
            }
        }


    }
}
