<?php

use Illuminate\Database\Seeder;

use GuzzleHttp\Client;

class GatheringAirfranceKLMAPIFlightFaresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $apiKey = '4kmnf3mnrk5ne5s53hk6xqvx';

        $url1 = 'https://api.klm.com/opendata/flightoffers/reference-data';
        $url2 = 'https://api.klm.com/opendata/flightoffers/available-offers';

        $airports = [];

        $airports[0] = DB::table('airports')->select(['*'])->where([
            ['country', '=', 'Germany'],
            ['city', '=', 'Berlin'],
        ])->WhereNotNull('iata')->get();

        $airports[1] = DB::table('airports')->select(['*'])->where([
            ['country', '=', 'United Kingdom'],
            ['city', '=', 'London'],
        ])->WhereNotNull('iata')->get();


        foreach ($airports[0] as $airport1) {
            foreach ($airports[1] as $airport2) {

                $iata1 = $airport1->iata;
                $iata2 = $airport2->iata;

                $headers = [
                    'accept' => 'application/hal+json;profile=com.afklm.b2c.flightoffers.available-offers.v1;charset=utf8',
                    'accept-language' => 'en-US',
                    'afkl-travel-country' => 'NL',
                    'afkl-travel-host' => 'AF',
                    'api-key' => $apiKey,
                    'content-type' => 'application/json',
                ];

                $body = '{
                  "cabinClass":"ECONOMY",
                  "discountCode":"",
                  "passengerCount":{
                    "YOUNG_ADULT":1,
                    "INFANT":1,
                    "CHILD":1,
                    "ADULT":2
                  },
                  "currency":"EUR",
                  "minimumAccuracy":90,
                  "requestedConnections":[
                    {
                      "origin":{
                        "airport":{
                          "code":"' . $iata1 . '"
                        }
                      },
                      "destination":{
                        "airport":{
                          "code":"' . $iata2 . '"
                        }
                      },
                      "departureDate":"2019-01-31"
                    }
                  ],
                  "shortest":true
                }';

                try {
                    usleep(250000) ;
                    $client = new Client();
                    $response = $client->request('POST', $url2, [
                        'json' => json_decode($body),
                        'headers' => $headers
                    ]);

                    $response = json_decode($response->getBody());


                } catch (\Exception $ex) {
                    echo 'Incompleted =' . $iata1 . ' & ' . $iata2 . "\n";
                }
                if (isset($response)) {
                    foreach ($response->flightProducts as $instance) {
                        DB::table('hotels')->insert([
                            'all_data' => gzcompress($instance),
                            'source' => 'api.klm.com/opendata/flightoffers/',
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()')
                        ]);
                        echo 'Completed =' . $iata1 . ' & ' . $iata2 . "\n";
                    }
                }
            }
        }


    }
}
