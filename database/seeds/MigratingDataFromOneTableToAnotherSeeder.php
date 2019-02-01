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

        $results = DB::table('cities_world_population')->select('*')->get();

        foreach ($results as $instance) {

            $results1=DB::table('cities')->select('*')->where('name','=',$instance->city)->get();
            dd($results1);

            DB::table('cities')
                ->where([
                    ['name', $instance->city],
                    ['country_code', $instance->iso2],
                    ])
                ->update([
                    'administrative_name' => $instance->admin_name,
                    'name_ascii' => $instance->city_ascii,
                    'population' => $instance->population,
                ]);

//            DB::table('cities')->insert([
//                'uid' => uniqid(),
//                's_no' => ++$j,
//                'id' => $instance->id,
//                'name' => $instance->name,
//                'country_code' => (isset($results1[0]->country_code) ? $results1[0]->country_code : null),
//                'country' => (isset($results1[0]->name) ? $results1[0]->name : null),
//                'latitude' => $instance->coordlat,
//                'longitude' => $instance->coordlon,
//                'source' => 'openweathermap.org',
//                'created_at' => DB::raw('now()'),
//                'updated_at' => DB::raw('now()')
//            ]);

            echo $j++ . ' city-> ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
        }
    }
}


/*

$results = DB::table('cities_1')->select('*')->get();

        foreach ($results as $instance) {

            $results1 = DB::table('countries')->select('*')->where('country_code', '=', $instance->country)->get();

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
        $results = DB::table('apps_countries_detailed')->select('*')->get();

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
                'source' => 'github.com/raramuridesign/mysql-country-list/blob/master/mysql-country-list-detailed-info.sql',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }
        */
