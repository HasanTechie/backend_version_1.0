<?php

use Illuminate\Database\Seeder;

class MigratingDataFromOneTableToAnotherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $j = 0;

        //http://www.freecountrymaps.com/databases/germany/
        $results1 = DB::table('z_ignore_germany_population')->select('*')->where('population', '<>', 0)->inRandomOrder()->get();

        $array = [];

        foreach ($results1 as $instance1) {

            $results2 = DB::table('cities')->select('*')->where([
                ['name', '=', $instance1->name],
                ['country_code', '=', $instance1->country_code]
            ])->whereNull('name_ascii')->get();

            foreach ($results2 as $instance2) {
                $lat1 = round($instance1->latitude, 2);
                $lng1 = round($instance1->longitude, 2);

                $lat2 = round($instance2->latitude, 2);
                $lng2 = round($instance2->longitude, 2);

                if (($lat1 == $lat2) && ($lng1 == $lng2)) {
                    $array[0] = $instance1;
                    $array[1] = $instance2;

                    DB::table('cities')
                        ->where([
                            ['name', $instance1->name],
                            ['country_code', $instance1->country_code],
                            ['id', $instance2->id],
                        ])
                        ->update([
                            'type' => $instance1->type,
                            'population' => $instance1->population,
                        ]);

                    echo $instance2->s_no . ' done -> ' . $instance2->name . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                }
            }
        }


    }
}

/*

$results = DB::table('cities_1')->select(' * ')->get();

        foreach ($results as $instance) {

            $results1 = DB::table('countries')->select(' * ')->where('country_code', ' = ', $instance->country)->get();

            DB::table('cities')->insert([
                'uid' => uniqid(),
                's_no' => ++$j,
                'id' => $instance->id,
                'name' => $instance->name,
                'country_code' => (isset($results1[0]->country_code) ? $results1[0]->country_code : null),
                'country' => (isset($results1[0]->name) ? $results1[0]->name : null),
                'latitude' => $instance->coordlat,
                'longitude' => $instance->coordlon,
                'source' => 'openweathermap.org',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

            echo $j . ' city-> ' . $instance->name . Carbon\Carbon::now()->toDateTimeString() . "\n";
        }

*/

/*
        $results = DB::table('apps_countries_detailed')->select(' * ')->get();

        foreach ($results as $instance) {
            DB::table('countries')->insert([
                'uid' => uniqid(),
                's_no' => ++$j,
                'country_code' => $instance->countryCode,
                'name' => $instance->countryName,
                'capital' => $instance->capital,
                'currency' => $instance->currencyCode,
                'fips_code' => $instance->fipsCode,
                'iso_numeric' => $instance->isoNumeric,
                'north' => $instance->north,
                'east' => $instance->east,
                'south' => $instance->south,
                'west' => $instance->west,
                'continent' => $instance->continentName,
                'continent_code' => $instance->continent,
                'languages' => $instance->languages,
                'iso_alpha3' => $instance->isoAlpha3,
                'geoname_id' => $instance->geonameId,
                'source' => 'github . com / raramuridesign / mysql - country - list/blob / master / mysql - country - list-detailed - info . sql',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }
*/
