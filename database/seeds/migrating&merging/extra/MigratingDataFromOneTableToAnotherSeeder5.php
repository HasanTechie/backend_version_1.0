<?php

use Illuminate\Database\Seeder;

class MigratingDataFromOneTableToAnotherSeeder5 extends Seeder
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
        $results1 = DB::table('z_ignore_germany_population')->select('*')->get();

        foreach ($results1 as $instance1) {

            $cid = round($instance1->latitude, 2) . '&' . round($instance1->longitude, 2);
            if ((DB::table('cities')->where('cid', '=', $cid)->doesntExist()) && (DB::table('cities')->where('name', '=', $instance1->name)->doesntExist())) {
                if ($result = DB::table('cities')->orderBy('s_no', 'desc')->first()) {
                    $j = $result->s_no;
                } else {
                    $j = 0;
                }
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

                echo $j . ' Completed city-> ' . Carbon\Carbon::now()->toDateTimeString() . ' ' . $instance1->name . "\n";
            }
        }


    }
}


/*                $results2 = DB::table('cities')->select(DB::raw('uid, ROUND(latitude,1) as lati , ROUND(longitude,1) as longi'))->havingRaw(
                    'lati=' . round($instance1->latitude, 1) . ' AND longi=' . round($instance1->longitude, 1) . ' '
                )->get();

                if (count($results2) < 1) {*/
