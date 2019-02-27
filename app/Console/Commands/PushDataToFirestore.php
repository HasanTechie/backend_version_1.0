<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use FirestoreSeeder;


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
        $new = new FirestoreSeeder();
        $new->run();
        echo 'PushDataToFireBase ended at : ' . Carbon::now()->toDateTimeString() . "\n\n";
    }
}
