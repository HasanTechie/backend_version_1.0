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
            array("4f0a5b53fe164cb74f2f7d055193c806", "maxbiocca"),
            array("22597326eeaf6f8ba8db0563ff8d0edc", "hasanabbax"),
        );
        $k = 0;

        $cities = DB::table('cities')->select('*')->where('country_code', '=', 'DE')->get();

        foreach ($cities as $city) {

            $client = new Client();
            $res = $client->request('GET', "https://api.openweathermap.org/data/2.5/forecast?id=$city->id&appid=" . $apiArray[$k][0], [
                'auth' => ['user', 'pass']
            ]);
            $response = json_decode($res->getBody());

            if (!empty($response) && $res->getStatusCode() == 200) {
                

            }
        }
    }
}
