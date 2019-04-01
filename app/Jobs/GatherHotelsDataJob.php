<?php

namespace App\Jobs;

use Hotels_eurobookings_SeederPC;
use Hotels_eurobookings_Seeder;
use Hotels_hrs_Seeder;

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
            $myClass = new Hotels_eurobookings_Seeder();
            $myClass->mainRun($this->hotelsBasicData);
        }
        if ($this->hotelsBasicData['source'] == 'hrs.com') {
            $myClass = new Hotels_hrs_Seeder();
            $myClass->mainRun($this->hotelsBasicData);
        }

    }
}
