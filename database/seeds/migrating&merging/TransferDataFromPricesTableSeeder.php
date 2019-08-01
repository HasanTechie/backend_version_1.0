<?php

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class TransferDataFromPricesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $dateToCompare = date("Y-m-d", strtotime("-2 day"));//only keep past 2days data.

//        $prices = DB::table('prices_hrs')->get(); //alot of  RAM consumption

        $firstId = DB::table('prices_hrs')->first();
        $lastId = DB::table('prices_hrs')->latest()->first();


        for ($i = $firstId->id; $i < $lastId->id; $i++) {
            $price = DB::table('prices_hrs')->where('id', '=', $i)->get();
            if (count($price) > 0) {
                if ($price[0]->request_date <= $dateToCompare) {
                    DB::table('prices_hrs')->where('id', '=', $price[0]->id)->delete();
                    DB::table('prices_hrs_data')->insert((array)$price[0]);
                }
            }
        }
    }
}
