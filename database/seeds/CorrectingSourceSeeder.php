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
        DB::table('routes')
            ->where('source', 'tour-pedia.org/api/')
            ->update(['source' => 'openflights.org']);
    }
}
