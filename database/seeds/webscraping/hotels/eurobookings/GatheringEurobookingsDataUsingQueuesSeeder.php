<?php

use App\Jobs\GatherHotelsDataEurobookingsJob;

use Illuminate\Database\Seeder;

class GatheringEurobookingsDataUsingQueuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $eurobookingsBasicData = DB::table('hotels_basic_data_for_gathering')->get();

        for ($k = -1; $k <= 5; $k++) {
            foreach ($eurobookingsBasicData as $instance) {
                $instance = (array)$instance;
                $instance['k'] = $k;
                $instance['start_date'] = '2019-04-10';
                $instance['end_date'] = '2019-04-10';
                GatherHotelsDataEurobookingsJob::dispatch($instance)->delay(now()->addSecond(1));
            }
        }
        echo "started Queue" . "\n";
    }
}
