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

        $results = DB::table('cities_1')->select('*')->get();

        foreach ($results as $instance) {
            DB::table('cities')->insert([
                'uid' => uniqid(),
                's_no' => ++$j,
                'source' => 'openweathermap.org',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

            echo 'hotels_uncompressed ' . '->' . $j . "\n";
        }


    }
}
