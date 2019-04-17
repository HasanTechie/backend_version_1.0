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
        $hrsHotelsBasicData = DB::table('hotels_basic_data_for_gathering')->where('source', '=', 'hrs.com')->whereIn('city', ['Rome', 'Berlin'])->get();

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
            $instance['currency'] = 'EUR';
            $instance['start_date'] = date("Y-m-d", strtotime("+5 day"));
            $instance['end_date'] = date("Y-m-d", strtotime("+5 day"));
            GatherHotelsDataJob::dispatch($instance)->delay(now()->addSecond(5));
        }
        echo "started Queue" . "\n";
    }
}
