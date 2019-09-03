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

        $dA['start_date'] = date("Y-m-d", strtotime("+1 day"));
        $dA['end_date'] = date("Y-m-d", strtotime("+365 day"));


        while (strtotime($dA['start_date']) <= strtotime($dA['end_date'])) {

            $dA['check_in_date'] = $dA['start_date'];
            $dA['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));

            DB::table('price_hrs')->insert(
                [
                    'price' => 49,
                    'currency' => '€',
                    'number_of_adults_in_room_request' => 2,
                    'check_in_date' => $dA['check_in_date'],
                    'check_out_date' => $dA['check_out_date'],
                    'room_id' => 40259,
                    'request_date' => date("Y-m-d"),
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]
            );

            $dA['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));
        }
    }
}
