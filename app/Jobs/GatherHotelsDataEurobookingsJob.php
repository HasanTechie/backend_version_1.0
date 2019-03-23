<?php

namespace App\Jobs;

use GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GatherHotelsDataEurobookingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eurobookingBasicData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($eurobookingBasicData)
    {
        //
        $this->eurobookingBasicData = $eurobookingBasicData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $myClass = new GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder();
        $myClass->mainRun($this->eurobookingBasicData);
    }
}
