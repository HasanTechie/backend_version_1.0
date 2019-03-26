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
        $eurobookingsHotelsBasicData = DB::table('hotels_basic_data_for_gathering')->where('source', '=', 'eurobookings.com')->inRandomOrder()->get();
        for ($k = -1; $k <= 5; $k++) {
            foreach ($eurobookingsHotelsBasicData as $instance) {
                $instance = (array)$instance;
                $instance['start_date'] = '2019-04-9';
                $instance['end_date'] = '2019-04-9';
                $instance['k'] = $k;
                GatherHotelsDataJob::dispatch($instance)->delay(now()->addSecond(1));
            }
        }
        echo "started Queue of Eurobookings" . "\n";


        $hrsHotelsBasicData = DB::table('hotels_basic_data_for_gathering')->where('source', '=', 'hrs.com')->inRandomOrder()->get();
        foreach ($hrsHotelsBasicData as $instance) {
            $instance = (array)$instance;
            $instance['start_date'] = '2019-04-9';
            $instance['end_date'] = '2019-04-9';
            GatherHotelsDataJob::dispatch($instance)->delay(now()->addSecond(1));
        }
        echo "started Queue of HRS" . "\n";
    }
}
