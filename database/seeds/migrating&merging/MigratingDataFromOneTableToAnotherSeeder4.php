<?php

use Illuminate\Database\Seeder;

class MigratingDataFromOneTableToAnotherSeeder4 extends Seeder
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
        $results1 = DB::table('z_ignore_cities_world_population')->select('*')->get();

        foreach ($results1 as $instance1) {


            $cid = round($instance1->lat, 2) . '&' . round($instance1->lng, 2);
            if ((DB::table('cities')->where('cid', '=', $cid)->doesntExist()) && (DB::table('cities')->where('name', '=', $instance1->city_ascii)->doesntExist())) {
                if ($result = DB::table('cities')->orderBy('s_no', 'desc')->first()) {
                    $j = $result->s_no;
                } else {
                    $j = 0;
                }
                DB::table('cities')->insert([
                    'uid' => uniqid(),
                    's_no' => ++$j,
                    'name' => $instance1->city_ascii,
                    'country_code' => (isset($instance1->iso2) ? $instance1->iso2 : null),
                    'country' => (isset($instance1->country) ? $instance1->country : null),
                    'latitude' => $instance1->lat,
                    'longitude' => $instance1->lng,
                    'administrative_name' => $instance1->admin_name,
                    'name_ascii' => $instance1->city_ascii,
                    'population' => $instance1->population,
                    'cid' => $cid,
                    'source' => 'simplemaps.com/data/world-cities',
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);

                echo $j . ' Completed city-> ' . Carbon\Carbon::now()->toDateTimeString() . ' ' . $instance1->city_ascii . "\n";
            }
        }


    }
}

/*
//simplemaps.com/data/world-cities
        $results1 = DB::table('z_ignore_cities_world_population')->select('*')->get();

        foreach ($results1 as $instance1) {

            $results2 = DB::table('cities')->select('*')->get();

            foreach ($results2 as $instance2) {


                $lat1 = round($instance1->lat, 1);
                $lng1 = round($instance1->lng, 1);

                $lat2 = round($instance2->latitude, 1);
                $lng2 = round($instance2->longitude, 1);

                if (($lat1 != $lat2) && ($lng1 != $lng2)) {
                    if ($result = DB::table('cities')->orderBy('s_no', 'desc')->first()) {
                        $j = $result->s_no;
                    } else {
                        $j = 0;
                    }
                    $cid = round($instance1->lat, 2) . '&' . round($instance1->lng, 2);
                    if (!(DB::table('cities')->where('cid', '=', $cid)->exists())) {
                        DB::table('cities')->insert([
                            'uid' => uniqid(),
                            's_no' => ++$j,
                            'name' => $instance1->city_ascii,
                            'country_code' => (isset($instance1->iso2) ? $instance1->iso2 : null),
                            'country' => (isset($instance1->country) ? $instance1->country : null),
                            'latitude' => $instance1->lat,
                            'longitude' => $instance1->lng,
                            'administrative_name' => $instance1->admin_name,
                            'name_ascii' => $instance1->city_ascii,
                            'population' => $instance1->population,
                            'cid' => $cid,
                            'source' => 'simplemaps.com/data/world-cities',
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()')
                        ]);

                        echo $j . ' Completed city-> ' . Carbon\Carbon::now()->toDateTimeString() . ' ' . $instance1->city_ascii . "\n";
                    }
                }
            }
        }
*/

/*
        //simplemaps.com/data/world-cities
        $results1 = DB::table('z_ignore_cities_world_population')->select('*')->get();

        if ($result = DB::table('cities')->orderBy('s_no', 'desc')->first()) {
            $j = $result->s_no;
        } else {
            $j = 0;
        }

        foreach ($results1 as $instance1) {

            $cid = round($instance1->lat, 2) . '&' . round($instance1->lng, 2);

            if (!(DB::table('cities')->where('cid', '=', $cid)->exists())) {
                DB::table('cities')->insert([
                    'uid' => uniqid(),
                    's_no' => ++$j,
                    'name' => $instance1->city_ascii,
                    'country_code' => (isset($instance1->iso2) ? $instance1->iso2 : null),
                    'country' => (isset($instance1->country) ? $instance1->country : null),
                    'latitude' => $instance1->lat,
                    'longitude' => $instance1->lng,
                    'administrative_name' => $instance1->admin_name,
                    'name_ascii' => $instance1->city_ascii,
                    'population' => $instance1->population,
                    'cid' => $cid,
                    'source' => 'simplemaps.com/data/world-cities',
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);

                echo $j . 'Completed city-> ' . $instance1->city_ascii . Carbon\Carbon::now()->toDateTimeString() . "\n";
            } else {
                echo $j . 'Existeddd city-> ' . $instance1->city_ascii . Carbon\Carbon::now()->toDateTimeString() . "\n";
            }
        }
*/

/*
//freecountrymaps.com/databases/germany
        $results1 = DB::table('z_ignore_germany_population')->select('*')->get();

        if ($result = DB::table('cities')->orderBy('s_no', 'desc')->first()) {
            $j = $result->s_no;
        } else {
            $j = 0;
        }

        foreach ($results1 as $instance1) {

            $cid = round($instance1->latitude, 2) . '&' . round($instance1->longitude, 2);

            if (!(DB::table('cities')->where('cid', '=', $cid)->exists())) {
                DB::table('cities')->insert([
                    'uid' => uniqid(),
                    's_no' => ++$j,
                    'name' => $instance1->name,
                    'country_code' => (isset($instance1->country_code) ? $instance1->country_code : null),
                    'country' => (isset($instance1->country) ? $instance1->country : null),
                    'latitude' => $instance1->latitude,
                    'longitude' => $instance1->longitude,
                    'type' => $instance1->type,
                    'population' => $instance1->population,
                    'cid' => $cid,
                    'source' => 'freecountrymaps.com/databases/germany',
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);

                echo $j . 'Completed city-> ' . $instance1->name . Carbon\Carbon::now()->toDateTimeString() . "\n";
            } else {
                echo $j . 'Existeddd city-> ' . $instance1->name . Carbon\Carbon::now()->toDateTimeString() . "\n";
            }
        }
*/
