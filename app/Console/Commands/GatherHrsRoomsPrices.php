<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Rooms_hrs_Queues_Seeder;

use Illuminate\Console\Command;

class GatherHrsRoomsPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gatherhrsroomsprices';

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
        echo 'GatherHrsRooms Command started at : ' . Carbon::now()->toDateTimeString() . "\n";

        $instance = new Rooms_hrs_Queues_Seeder();
        $instance->run();

        echo 'GatherHrsRooms Command ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
