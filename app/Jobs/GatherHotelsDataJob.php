<?php

namespace App\Jobs;

use GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GatherHotelsDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $hotelsBasicData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hotelsBasicData)
    {
        //
        $this->hotelsBasicData = $hotelsBasicData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->hotelsBasicData['source'] == 'eurobookings.com') {
            for ($k = -1; $k <= 5; $k++) {
                $this->hotelsBasicData['k'] = $k;
                $myClass = new GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder();
                $myClass->mainRun($this->hotelsBasicData);
            }
        }
//        if ($this->hotelsBasicData['source'] == 'hrs.com') {
//            $myClass = new GatheringHotels_eurobookingsdotcom_Hotels_ScrapingDataSeeder();
//            $myClass->mainRun($this->hotelsBasicData);
//        }

    }
}
