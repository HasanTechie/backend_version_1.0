<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GenerateManualPriceSeeder;

use Illuminate\Console\Command;

class GenerateManualPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generatemanualprices';

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
        echo 'GenerateManualPrice Command started at : ' . Carbon::now()->toDateTimeString() . "\n";

        $instance = new GenerateManualPriceSeeder();
        $instance->run();

        echo 'GenerateManualPrice Command ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
