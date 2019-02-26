<?php

namespace App\Console\Commands;

use GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain;
use Carbon\Carbon;

use Illuminate\Console\Command;


class GatherEurobookingsBerlinData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gathereurobookingsberlindata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        echo 'GatherEurobookingsBerlinData started at : ' . Carbon::now()->toDateTimeString() . "\n";

        $dataArray = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-03-01',
            'end_date' => '2019-04-30',
            'city' => 'Berlin',
            'city_id' => 536,
            'country' => 'Germany',
            'total_results' => 800,
        ];

        $new = new GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain();
        $new->mainRun($dataArray);

        echo 'GatherEurobookingsBerlinData ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
