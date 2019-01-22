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

        $cities = ['Berlin', 'Cologne', 'Frankfurt', 'Hamburg', 'Munich'];

        $dest_cities = [
            'France' => 'Paris',
            'United Kingdom' => 'London',
            'Italy' => 'Rome',
            'Spain' => 'Barcelona',
            'Romania' => 'Bucharest'];

        foreach ($cities as $city) {
            foreach ($dest_cities as $country => $city2) {


                $airports[0] = DB::table('airports')->select(['*'])->where([
                    ['country', '=', 'Germany'],
                    ['city', '=', $city],
                ])->WhereNotNull('iata')->get();

                $airports[1] = DB::table('airports')->select(['*'])->where([
                    ['country', '=', $country],
                    ['city', '=', $city2],
                ])->WhereNotNull('iata')->get();

                $airline[0] = 'AF';
                $airline[1] = 'KL';
                for ($j = 0; $j <= 1; $j++) {

                    $currentAirline = $airline[$j];

                    for ($i = 1; $i <= 28; $i++) {
                        foreach ($airports[0] as $airport1) {
                            foreach ($airports[1] as $airport2) {

                                $iata1 = $airport1->iata;
                                $iata2 = $airport2->iata;

                                $name1 = $airport1->name;
                                $name2 = $airport2->name;

                                if ($i < 10) {
                                    $date = "2019-02-0$i";
                                } else {
                                    $date = "2019-02-$i";
                                }

                                $headers = [
                                    'accept' => 'application/hal+json;profile=com.afklm.b2c.flightoffers.available-offers.v1;charset=utf8',
                                    'accept-language' => 'en-US',
                                    'afkl-travel-country' => 'DE',
                                    'afkl-travel-host' => $currentAirline,
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
                                      "departureDate":"' . $date . '"
                                    }
                                  ],
                                  "shortest":true
                                }';

                                try {
                                    usleep(250000);
                                    $client = new Client();
                                    $response = $client->request('POST', $url2, [
                                        'json' => json_decode($body),
                                        'headers' => $headers
                                    ]);

                                    $response = json_decode($response->getBody());


                                } catch (\Exception $ex) {
                                    echo 'Incompleted =  ' . $currentAirline . ' ' . $name1 . ' (' . $iata1 . ') & ' . $name2 . '(' . $iata2 . ')' . "\n";
                                }
                                if (isset($response)) {
                                    foreach ($response->flightProducts as $instance) {
                                        DB::table('flights_afklm_api')->insert([
                                            'airline_code' => $currentAirline,
                                            'origin_name' => $name1,
                                            'origin_iata' => $iata1,
                                            'destination_name' => $name2,
                                            'destination_iata' => $iata1,
                                            'flight_date' => $date,
                                            'all_data' => serialize($instance),
                                            'source' => 'api.klm.com/opendata/flightoffers/',
                                            'created_at' => DB::raw('now()'),
                                            'updated_at' => DB::raw('now()')
                                        ]);
                                        echo 'Completed =  ' . $currentAirline . ' ' . $name1 . ' (' . $iata1 . ') & ' . $name2 . '(' . $iata2 . ')' . "\n";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


    }
}
