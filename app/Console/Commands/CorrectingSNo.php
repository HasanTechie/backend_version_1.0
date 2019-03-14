<?php

namespace App\Console\Commands;

use CorrectingSNoSeeder;
use Carbon\Carbon;

use Illuminate\Console\Command;

class CorrectingSNo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:correctingsno';

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
        echo 'CorrectingSNo started at : ' . Carbon::now()->toDateTimeString() . "\n";
        $new = new CorrectingSNoSeeder();
        $new->run();
        echo 'CorrectingSNo ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
