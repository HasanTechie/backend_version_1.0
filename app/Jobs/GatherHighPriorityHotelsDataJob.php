<?php

namespace App\Jobs;

use Carbon\Carbon;
use Exception;
use Hotels_hrs_high_priority_Seeder;
use Illuminate\Support\Facades\Storage;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GatherHighPriorityHotelsDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try {
            $myClass = new Hotels_hrs_high_priority_Seeder();
            $myClass->run();
        } catch (Exception $e) {
            Storage::append('hrs/HotelsFailedTCJobs' . date("Y-m-d") . '.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
        }
    }
}
