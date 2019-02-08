<?php

use Illuminate\Database\Seeder;

class MigratingDataFromOneTableToAnotherSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //freecountrymaps.com/databases/germany
        $results1 = DB::table('z_ignore_germany_population')->select('*')->where('population', '>', 0)->get();

        $array = [];
        $i = 0;
        foreach ($results1 as $instance1) {

            $results2 = DB::table('cities')->select('*')->where([
                ['name', '=', $instance1->name],
                ['country_code', '=', $instance1->country_code],
                ['population', '=', null]
            ])->get();

            foreach ($results2 as $instance2) {
                $lat1 = round($instance1->latitude, 1);
                $lng1 = round($instance1->longitude, 1);

                $lat2 = round($instance2->latitude, 1);
                $lng2 = round($instance2->longitude, 1);

                if (($lat1 == $lat2) && ($lng1 == $lng2)) {
//                    $array[0] = $instance1;
//                    $array[1] = $instance2;

                    DB::table('cities')
                        ->where([
                            ['country_code', $instance2->country_code],
                            ['cid', $instance2->cid],
                        ])
                        ->update([
                            'type' => $instance1->type,
                            'population' => $instance1->population,
                            'source' => 'openweathermap.org, simplemaps.com/data/world-cities, freecountrymaps.com/databases/germany',
                        ]);

                    echo ++$i . ' ' . $instance2->s_no . ' done -> ' . $instance2->name . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                }
            }
        }
    }
}
