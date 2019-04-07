<?php

namespace App\Console\Commands;

use GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain;
use Carbon\Carbon;

use Illuminate\Console\Command;

class GatherEurobookingsRomeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gathereurobookingsromedata';

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
        /*echo 'GatherEurobookingsRomeData started at : ' . Carbon::now()->toDateTimeString() . "\n";

        $dataArray = [
            'adults' => 2,
            'currency' => 'EUR',
            'start_date' => '2019-02-26',
            'end_date' => '2020-02-26',
            'city' => 'Rome',
            'city_id' => 3023,
            'country' => 'Italy',
            'total_results' => 2000,
        ];

        $new = new GatheringHotels_eurobookingsdotcom_ScrapingDataSeederMain();
        $new->mainRun($dataArray);

        echo 'GatherEurobookingsRomeData ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";*/
    }
}
