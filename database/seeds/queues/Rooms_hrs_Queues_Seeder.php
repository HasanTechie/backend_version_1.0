<?php

use App\Jobs\GatherRoomsDataJob;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class Rooms_hrs_Queues_Seeder extends Seeder
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

        $hotels = DB::table('hotels_hrs')->select('id', 'hrs_id', 'city')->whereIn('city', ['Rome', 'Berlin'])->get();

        foreach ($hotels as $hotel) {
            $dA['hotel_id'] = $hotel->id;
            $dA['hotel_hrs_id'] = $hotel->hrs_id;
            $dA['city'] = $hotel->city;

            GatherRoomsDataJob::dispatch($dA)->delay(now()->addSecond(mt_rand(5, 250)));
        }

    }
}
