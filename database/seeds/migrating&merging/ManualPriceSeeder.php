<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManualPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        dd(date("Y-m-d"));

        dd('tead');

        DB::table('price_hrs')->insert(
            [
                'price' => 49,
                'currency' => '€',
                'number_of_adults_in_room_request' => 2,
                'check_in_date' => 0,
                'check_out_date' => 0,
                'room_id' => 40259,
                'request_date' => date("Y-m-d"),
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]
        );
    }
}
