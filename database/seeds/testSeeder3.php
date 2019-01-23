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
        for ($i = 1; $i <= 300000; $i++) {
            if (DB::table('flights')->where('s_no', $i)->exists()) {

                $results = DB::table('flights')->select('s_no')->where('s_no', $i)->get();

                foreach ($results as $instance) {
                    $j++;

                    if ($j != $instance->s_no) {
                        DB::table('flights')->where('s_no', $instance->s_no)->update([
                            's_no' => $j,
                        ]);

                        echo ' done ';
                    }
                    echo 'flights ' . $j . "\n";
                }
            }
        }


    }
}
