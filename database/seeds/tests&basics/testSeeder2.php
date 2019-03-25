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

//        $hotelsBasicData = DB::table('hotels_basic_data_for_gathering')->inRandomOrder()->get();
//
//        $i = 0;
//        foreach ($hotelsBasicData as $instance) {
//            $i++;
//            echo $instance->city . ' ' . $instance->source . "\n";
//        }
//        echo $i;
//        if(rand(0,1)){
//            dd('1 reached');
//        }else{
//            dd('0 reached');
//        }
//        $j = 0;
//
//
//        $results = DB::table('rooms_prices_eurobookings')->get();
//
//        foreach ($results as $instance) {
//
//            DB::table('rooms_prices_eurobookings')->where('uid', $instance->uid)->update([
//                's_no' => ++$j,
//            ]);
//            echo 's_no. ' . $j . "\n";
//        }


    }
}
