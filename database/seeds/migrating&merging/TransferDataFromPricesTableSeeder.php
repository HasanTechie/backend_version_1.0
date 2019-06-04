<?php

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

        $dateToCompare = date("Y-m-d", strtotime("-7 day"));//only keep past 7days data.

        $prices = DB::table('prices_hrs')->get();
        foreach($prices as $price){
            if($price->request_date <= $dateToCompare){

            }
        }
    }
}
