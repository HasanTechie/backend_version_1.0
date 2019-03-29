<?php

use Illuminate\Database\Seeder;

class Hotels_hrs_SeederSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hrsBasicData = DB::table('hotels_basic_data_for_gathering')->where([
            ['source', '=', 'hrs.com'],
        ])->inRandomOrder()->get();

        $hrs = new Hotels_hrs_Seeder();
        foreach ($hrsBasicData as $instance) {
            $instance = (array)$instance;
            $instance['start_date'] = '2019-04-09';
            $instance['end_date'] = '2019-04-15';
            $hrs->mainRun($instance);
        }
    }
}
