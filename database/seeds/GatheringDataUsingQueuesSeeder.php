<?php

use App\Jobs\GatherHotelsDataJob;

use Illuminate\Database\Seeder;

class GatheringDataUsingQueuesSeeder extends Seeder
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
            $instance['start_date'] = '2019-04-09';
            $instance['end_date'] = '2019-04-09';

            $instanceArray [] = $instance;
        }
//        for ($k = -1; $k <= 5; $k++) {
//            foreach ($eurobookingsHotelsBasicData as $instance) {
//                $instance = (array)$instance;
//                $instance['start_date'] = '2019-04-09';
//                $instance['end_date'] = '2019-04-09';
//                $instance['k'] = $k;
//                $instanceArray [] = $instance;
//            }
//        }
        shuffle($instanceArray);

        foreach ($instanceArray as $instance) {
            GatherHotelsDataJob::dispatch($instance)->delay(now()->addSecond(2));
        }
        echo "started Queue" . "\n";
    }
}
