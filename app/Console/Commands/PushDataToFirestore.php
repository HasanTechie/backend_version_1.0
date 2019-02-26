<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use correctingSNoSeeder;


use Illuminate\Console\Command;


class PushDataToFirestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pushdatatofirestore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Data To Firestore From MySQL';

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
        echo 'PushDataToFireBase started at : ' . Carbon::now()->toDateTimeString() . "\n";
//        $tables = ['hotels_eurobookings', 'hotels_hrs', 'rooms_prices_eurobookings', 'rooms_prices_hrs'];
//        $tables = ['hotels_hrs'];
//
//        foreach ($tables as $table) {
//
//            $j = 0;
//            $results = DB::table($table)->get();
//
//            foreach ($results as $instance) {
//
//                DB::table($table)->where('uid', $instance->uid)->update([
//                    's_no' => ++$j,
//                ]);
////                echo 's_no. ' . $j . ' ' . $table . "\n";
//            }
//        }

        $new = new correctingSNoSeeder();
        $new->run();
        echo 'PushDataToFireBase ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
