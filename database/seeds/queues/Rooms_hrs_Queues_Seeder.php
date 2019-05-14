<?php

use App\Jobs\GatherRoomsDataJob;

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
        $dA['start_date'] = date("Y-m-d", strtotime("+1 day"));
        $dA['end_date'] = date("Y-m-d", strtotime("+240 day"));
        $dA['adults'] = [2];

        $hotels = DB::table('hotels_hrs')->select('id','hrs_id','city')->whereIn('city', ['Rome', 'Berlin'])->get();

        foreach ($hotels as $hotel) {
            $dA['hotel_id'] = $hotel->id;
            $dA['hotel_hrs_id'] = $hotel->hrs_id;
            $dA['city'] = $hotel->city;
            GatherRoomsDataJob::dispatch($hotel, $dA)->delay(now()->addSecond(2));
        }
    }
}
