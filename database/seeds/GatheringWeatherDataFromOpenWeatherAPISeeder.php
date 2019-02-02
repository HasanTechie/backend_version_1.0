<?php

use GuzzleHttp\Client;

use Illuminate\Database\Seeder;

class GatheringWeatherDataFromOpenWeatherAPISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $apiArray = Array(
            array("22597326eeaf6f8ba8db0563ff8d0edc", "hasanabbax"),
            array("4f0a5b53fe164cb74f2f7d055193c806", "maxbiocca"),
        );

        $k = 0;

        if ($result1 = DB::table('weathers')->orderBy('s_no', 'desc')->first()) {
            $j = $result1->s_no;
        } else {
            $j = 0;
        }

        $cities = DB::table('cities')->select('*')->orderBy('population', 'desc')->get();

        foreach ($cities as $city) {

            if (!(DB::table('weathers')->where('city_id', '=', $city->id)->exists())) {
                try {
                    usleep(500000);
                    $client = new Client();
                    $res = $client->request('GET', "https://api.openweathermap.org/data/2.5/forecast?id=$city->id&appid=" . $apiArray[$k][0], [
                        'auth' => ['user', 'pass']
                    ]);
                    $response = json_decode($res->getBody());

                } catch (\Exception $ex) {
                    if (!empty($ex)) {
                        $k++;
                        echo $ex->getMessage();
                    }
                }


                if (!empty($response) && $res->getStatusCode() == 200) {

                    DB::table('weathers')->insert([
                        'uid' => uniqid(),
                        's_no' => ++$j,
                        'city_id' => $city->id,
                        'city_cid' => $city->cid,
                        'city' => $city->name,
                        'location_type' => $city->type,
                        'country' => $city->country,
                        'latitude' => $city->latitude,
                        'longitude' => $city->longitude,
                        'weather_data' => serialize($response),
                        'source' => 'openweathermap.org',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);

                    echo $j . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Completed city-> ' . $city->name . ' population ->' . $city->population . "\n";

                }
            } else {
                echo $j . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Existeddd city-> ' . $city->name . ' population ->' . $city->population . "\n";
            }
        }
    }
}
