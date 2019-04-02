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

        foreach ($hrsBasicData as $instance) {
            $hrs = new Hotels_hrs_Seeder();
            $instance = (array)$instance;
            $instance['currency'] = 'EUR';
            $instance['start_date'] = '2019-04-14';
            $instance['end_date'] = '2019-04-14';
            $hrs->mainRun($instance);
        }
    }
}
