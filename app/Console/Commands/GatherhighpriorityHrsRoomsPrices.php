<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Rooms_hrs_high_priority_Queues_Seeder;

use Illuminate\Console\Command;

class GatherhighpriorityHrsRoomsPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gatherhighpriorityhrsroomsprices';

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
        //
        echo 'GatherhighpriorityHrsRoomsPrices Command started at : ' . Carbon::now()->toDateTimeString() . "\n";

        $instance = new Rooms_hrs_high_priority_Queues_Seeder();
        $instance->run();

        echo 'GatherhighpriorityHrsRoomsPrices Command ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
