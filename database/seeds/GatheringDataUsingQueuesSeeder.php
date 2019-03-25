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
        $hotelsBasicData = DB::table('hotels_basic_data_for_gathering')->get();

        foreach ($hotelsBasicData as $instance) {
            $instance = (array)$instance;
            $instance['start_date'] = '2019-04-10';
            $instance['end_date'] = '2019-04-10';
            GatherHotelsDataJob::dispatch($instance)->delay(now()->addSecond(1));
        }
        echo "started Queue" . "\n";
    }
}
