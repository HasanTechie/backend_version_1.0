<?php

use Illuminate\Database\Seeder;

class CorrectingSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('cities')
            ->whereNotNull('name_ascii')
            ->whereNotNull('type')
            ->update(['source' => 'openflights.org, simplemaps.com/data/world-cities, freecountrymaps.com/databases/germany']);
    }
}

/*
            DB::table('routes')
            ->where('source', 'tour-pedia.org/api/')
            ->update(['source' => 'openflights.org']);
*/
