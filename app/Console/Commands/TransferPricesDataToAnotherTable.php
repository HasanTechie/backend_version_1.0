<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use TransferDataFromPricesTableSeeder;

use Illuminate\Console\Command;

class TransferPricesDataToAnotherTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:transferpricesdatatoanothertable';

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
        echo 'TransferPricesDataToAnotherTable Command started at : ' . Carbon::now()->toDateTimeString() . "\n";

        $instance = new TransferDataFromPricesTableSeeder();
        $instance->run();

        echo 'TransferPricesDataToAnotherTable Command ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
