<?php

use GuzzleHttp\Client;

use Illuminate\Database\Seeder;

class TrawexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        $headers = [
            'accept-language' => 'en-US',
            'content-type' => 'application/json',
        ];

        $body = '{
                "user_id" : "MaxTest_API2019",
                "user_password": "MaxxmLTestAPI2019",
                "access" : "Test",
                "ip_address" : "52.58.155.145",
                "trackingId" : "5bbc605a186ea",
                "city_name" : "Berlin",
                "country_name" : "Germany",
                "room_count" : 2,
                "adult" : [2,1],
                "child" : {"0":0,"1":0},
                "child_age" : {"0":[0],"1":[0]},
                "checkin" : "2019-02-17",
                "checkout" : "2019-02-18",
                "client_nationality" : "DE",
                "requiredCurrency" : "EUR"
                 }';

        $url = 'https://trawex.travel/api/index.php/hotel_trawexv5/hotel_search';
        $client = new Client();

        $response = $client->request('POST', $url, [
            'json' => json_decode($body),
            'headers' => $headers
        ]);


        $response = json_decode($response->getBody());

        dd($response);
    }
}
