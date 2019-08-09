<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Rooms_high_priority_hrs_SeederSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $dA['currency'] = 'EUR';
        $dA['adults'] = [2];
        $dA['start_date'] = date("Y-m-d", strtotime("+1 day"));
        $dA['end_date'] = date("Y-m-d", strtotime("+365 day"));


        while (strtotime($dA['start_date']) <= strtotime($dA['end_date'])) {
            $dA['check_in_date'] = $dA['start_date'];
            $dA['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));

            echo 'GatherhighpriorityHrsRoomsPrices Command started at : ' . Carbon::now()->toDateTimeString() . "\n";

            $instance = new Rooms_high_priority_hrs_Seeder();
            $instance->run($dA);

            echo 'GatherhighpriorityHrsRoomsPrices Command ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";

//            GatherHighPriorityRoomsDataJob::dispatch($dA)->onQueue('high')->delay(now()->addSecond(2));

//            if ($dA['start_date'] < date("Y-m-d", strtotime("+180 day"))) {
            $dA['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));
//            } else {
//                $dA['start_date'] = date("Y-m-d", strtotime("+7 day", strtotime($dA['start_date'])));
//            }
        }
    }
}
