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
        $dA['start_date'] = '2019-04-17';
        $dA['end_date'] = '2019-04-30';
        $dA['adults'] = [1, 2];

        $hotels = DB::table('hotels_hrs')->inRandomOrder()->get();

        foreach ($hotels as $hotel) {
            GatherRoomsDataJob::dispatch($hotel, $dA)->delay(now()->addSecond(mt_rand(5,100)));
        }
    }
}
