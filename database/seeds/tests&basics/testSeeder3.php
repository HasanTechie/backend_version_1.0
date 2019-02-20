<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

class testSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Storage::append('url.log', 'blah' . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n");

    }
}
