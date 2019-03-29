<?php

use App\Jobs\GatherHotelsDataJob;

use Illuminate\Database\Seeder;

class Hotels_Queues_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
//        $eurobookingsHotelsBasicData = DB::table('hotels_basic_data_for_gathering')->where('source', '=', 'eurobookings.com')->get();
        $hrsHotelsBasicData = DB::table('hotels_basic_data_for_gathering')->where('source', '=', 'hrs.com')->get();

        $instanceArray = [];
        foreach ($hrsHotelsBasicData as $instance) {
            $instance = (array)$instance;

            $instanceArray [] = $instance;
        }
//        for ($k = -1; $k <= 5; $k++) {
//            foreach ($eurobookingsHotelsBasicData as $instance) {
//                $instance = (array)$instance;
//                $instance['k'] = $k;
//                $instanceArray [] = $instance;
//            }
//        }
        shuffle($instanceArray);

        foreach ($instanceArray as $instance) {
            $instance['start_date'] = '2019-04-10';
            $instance['end_date'] = '2019-04-10';
            GatherHotelsDataJob::dispatch($instance)->delay(now()->addSecond(2));
        }
        echo "started Queue" . "\n";
    }
}
