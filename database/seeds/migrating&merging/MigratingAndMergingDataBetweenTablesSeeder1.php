<?php

use Illuminate\Database\Seeder;

class MigratingAndMergingDataBetweenTablesSeeder1 extends Seeder
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


        /*

                $results1 = DB::table('z_ignore_cities')->select('*')->get();

                foreach ($results1 as $instance1) {

                    $results2 = DB::table('countries')->select('*')->where('country_code', ' = ', $instance1->country)->get();

                    $results3 = DB::table('z_ignore_cities_world_population')->select('city_ascii','lat','lng','admin_name','population')->where('city_ascii', ' = ', $instance1->name)->get();

                    if(count($results3) <1){
                        DB::table('cities')->insert([
                            'uid' => uniqid(),
                            's_no' => ++$j,
                            'id' => $instance1->id,
                            'name' => $instance1->name,
                            'country_code' => (isset($results2[0]->country_code) ? $results2[0]->country_code : null),
                            'country' => (isset($results2[0]->name) ? $results2[0]->name : null),
                            'latitude' => $instance1->coordlat,
                            'longitude' => $instance1->coordlon,
                            'source' => 'openweathermap.org',
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()')
                        ]);
                    }else{
                        foreach ($results3 as $instance3){

                            $lat1 = round($instance1->coordlat, 2);
                            $lng1 = round($instance1->coordlon, 2);

                            $lat2 = round($instance3->latitude, 2);
                            $lng2 = round($instance3->longitude, 2);

                            if (($lat1 == $lat2) && ($lng1 == $lng2)) {
                                $array[0] = $instance1;
                                $array[1] = $instance3;

                            DB::table('cities')->insert([
                                'uid' => uniqid(),
                                's_no' => ++$j,
                                'id' => $instance1->id,
                                'name' => $instance1->name,
                                'country_code' => (isset($results2[0]->country_code) ? $results2[0]->country_code : null),
                                'country' => (isset($results2[0]->name) ? $results2[0]->name : null),
                                'latitude' => $instance1->coordlat,
                                'longitude' => $instance1->coordlon,
                                'source' => 'openweathermap.org',
                                'created_at' => DB::raw('now()'),
                                'updated_at' => DB::raw('now()')
                            ]);

                        }

                    }



                    echo $j . ' city-> ' . $instance1->name . Carbon\Carbon::now()->toDateTimeString() . "\n";
                }

        */
    }
}
