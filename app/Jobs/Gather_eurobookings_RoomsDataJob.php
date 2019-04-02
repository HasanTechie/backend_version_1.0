<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Rooms_eurobookings_Seeder;

class Gather_eurobookings_RoomsDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dA, $hotelURL;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hotelURL, $dA)
    {
        //
        $this->dA = $dA;
        $this->hotelURL = $hotelURL;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $room = new Rooms_eurobookings_Seeder();

        $room->mainRun($this->hotelURL, $this->dA);
    }
}
