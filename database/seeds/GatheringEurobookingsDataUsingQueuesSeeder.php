<?php

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
        GatherHotelsDataEurobookingsJob::dispatch()->delay(now()->addSecond(1));
        echo "reached" . "\n";
    }
}
