<?php

use Illuminate\Database\Seeder;

class CorrectingSNoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

//        $tables = ['hotels_hrs'];
        $tables = [
            'hotels_eurobookings',
            'hotels_hrs',
            'rooms_prices_eurobookings',
            'rooms_prices_hrs',
            'hotels_basic_data_for_gathering'];

        foreach ($tables as $table) {

            $j = 0;
            $results = DB::table($table)->get();

            foreach ($results as $instance) {

                DB::table($table)->where('uid', $instance->uid)->update([
                    's_no' => ++$j,
                ]);
//                echo 's_no. ' . $j . ' ' . $table . "\n";
            }
        }
    }
}
