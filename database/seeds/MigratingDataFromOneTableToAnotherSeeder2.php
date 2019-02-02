<?php

use Illuminate\Database\Seeder;

class MigratingDataFromOneTableToAnotherSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        //simplemaps.com/data/world-cities
        $results1 = DB::table('z_ignore_cities_world_population')->select('*')->where('population', '>', 0)->get();

        $array = [];

        foreach ($results1 as $instance1) {

            $results2 = DB::table('cities')->select('*')->where([
                ['name', '=', $instance1->city_ascii],
                ['country_code', '=', $instance1->iso2]
            ])->whereNull('name_ascii')->get();

            foreach ($results2 as $instance2) {
                $lat1 = round($instance1->lat, 2);
                $lng1 = round($instance1->lng, 2);

                $lat2 = round($instance2->latitude, 2);
                $lng2 = round($instance2->longitude, 2);

                if (($lat1 == $lat2) && ($lng1 == $lng2)) {
                    $array[0] = $instance1;
                    $array[1] = $instance2;

                    DB::table('cities')
                        ->where([
                            ['country_code', $instance1->iso2],
                            ['cid', $instance2->cid],
                            ['id', $instance2->id],
                        ])
                        ->update([
                            'administrative_name' => $instance1->admin_name,
                            'name_ascii' => $instance1->city_ascii,
                            'population' => $instance1->population,
                            'source' => 'openweathermap.org, simplemaps.com/data/world-cities',
                        ]);

                    echo $instance2->s_no . ' done -> ' . $instance2->name . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                }
            }
        }

    }
}
