<?php

use App\Jobs\GatherHotelsDataJob;
use App\Jobs\GatherHighPriorityHotelsDataJob;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Hotels_Queues_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //empty job queue before sending new jobs.
        $notReservedJobs = DB::table('jobs')->select('id')->whereNull('reserved_at')->get();
        foreach($notReservedJobs as $notReservedJob){
            DB::table('jobs')->where('id', '=', $notReservedJob->id)->delete();
        }

        //
//        $eurobookingsHotelsBasicData = DB::table('hotels_basic_data_for_gathering')->where('source', '=', 'eurobookings.com')->get();
        $hrsHotelsBasicData = DB::table('hotels_basic_data_for_gathering')->where('source', '=', 'hrs.com')->get();

        $instanceArray = [];

        foreach ($hrsHotelsBasicData as $instance) {
            $instance = (array)$instance;

            $instanceArray [] = $instance;
        }
        /*for ($k = -1; $k <= 5; $k++) {
            foreach ($eurobookingsHotelsBasicData as $instance) {
                $instance = (array)$instance;
                $instance['k'] = $k;
                $instanceArray [] = $instance;
            }
        }*/
        shuffle($instanceArray);

        foreach ($instanceArray as $instance) {
            $instance['currency'] = 'EUR';

            GatherHotelsDataJob::dispatch($instance)->delay(now()->addSecond(5));
        }

        GatherHighPriorityHotelsDataJob::dispatch()->onQueue('high')->delay(now()->addSecond(5));
    }
}
