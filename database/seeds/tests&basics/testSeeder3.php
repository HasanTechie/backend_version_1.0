<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

use App\Jobs\GatherHotelsDataEurobookingsJob;
use App\Jobs\PushDataToFirestoreJob;

class testSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        PushDataToFirestoreJob::dispatch()->delay(now()->addSecond(1));
        echo "reached" . "\n";
    }
}
