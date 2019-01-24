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
            'Paris',
            'London',
            'Rome',
            'Barcelona',
            'Bucharest',
            'Moscow',
            'New York',
            'Milan',
            'Los Angeles',
            'San francisco',
            'Prague',
            'Amsterdam',
            'Stockholm',
            'Beijing',
            'Shanghai',
            'Tokyo',
            'Singapore',
            'Dubai',
            'Berlin',
            'Munich',
            'Frankfurt',
            'Hamburg',
            'Cologne',
            'Rio de janeiro',
            'Budapest',
            'Vienna',
            'Lisbon',
            'Venice',
            'Madrid',
            'Sao Paulo',
            'St. Petersburg'
        ];
        /*
        London,
        Paris,
        Rome,
        Burcharest,
        Barcelona,
        Moscow,
        New York,
        Saint Petersburg,
        Los Angeles,
        San francisco,
        Milan,
        Prague,
        Amsterdam,
        Stockholm,
        Munich,
        Frankfurt,
        Hamburg,
        Cologne,
        Beijing,
        Shanghai,
        Tokyo,
        Singapore,
        Dubai,
        Rio de janeiro,
        Saint Paolo,
        Budapest,
        Vienna,
        Lisbon,
        Venice,
        Madrid,
        */

        $j = 0;
        foreach ($cities as $city2) { //to here
            foreach ($dest_cities as $city1) { //from here


                $airports[0] = DB::table('airports')->select(['iata', 'name', 'city'])->where('city', '=', $city1)
                    ->WhereNotNull('iata')->get();

                $airports[1] = DB::table('airports')->select(['iata', 'name', 'city'])->where('city', '=', $city2)
                    ->WhereNotNull('iata')->get();


                $airline[0] = 'AF';
                $airline[1] = 'KL';
                for ($k = 0; $k <= 1; $k++) {

                    $currentAirline = $airline[$k];


                    foreach ($airports[0] as $airport1) {
                        foreach ($airports[1] as $airport2) {

                            $iata1 = $airport1->iata;
                            $iata2 = $airport2->iata;

                            $name1 = $airport1->name;
                            $name2 = $airport2->name;

                            // Set timezone
                            date_default_timezone_set('UTC');

                            // Start date
                            $date = '2019-02-02';
                            // End date
                            $end_date = '2019-07-31';

                            while (strtotime($date) <= strtotime($end_date)) {

                                $headers = [
                                    'accept' => 'application/hal+json;profile=com.afklm.b2c.flightoffers.available-offers.v1;charset=utf8',
                                    'accept-language' => 'en-US',
                                    'afkl-travel-country' => 'DE',
                                    'afkl-travel-host' => $currentAirline,
                                    'api-key' => $apiKey,
                                    'content-type' => 'application/json',
                                ];

                                $iata1 = 'TXL';
                                $iata2 = 'CDG';

                                $body = '{
                                  "cabinClass":"ECONOMY",
                                  "discountCode":"",
                                  "passengerCount":{
                                    "YOUNG_ADULT":1,
                                    "INFANT":1,
                                    "CHILD":1,
                                    "ADULT":1
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
                                    if (!empty($ex)) {
                                        echo 'Incompleted =  ' . $currentAirline . ' ' . $name1 . ' (' . $iata1 . ') & ' . $name2 . '(' . $iata2 . ')' . ' ' . $date . ' ' . "\n";
                                    }
                                }

                                if (isset($response)) {

                                    foreach ($response->flightProducts as $instance) {
                                        echo ($instance->connections[0]->duration) . "\n";
                                        echo ($instance->connections[0]->numberOfSeatsAvailable) . "\n";
                                        echo ($instance->connections[0]->segments[0]->arrivalDateTime) . "\n";
                                        echo ($instance->connections[0]->segments[0]->departureDateTime) . "\n";
                                        echo ($instance->connections[0]->segments[0]->destination->name) . "\n";
                                        echo ($instance->connections[0]->segments[0]->destination->city->name) . "\n";
                                        echo ($instance->connections[0]->segments[0]->destination->city->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->destination->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->carrier->name) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->carrier->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->numberOfStops) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->operatingFlight->equipmentType->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->operatingFlight->equipmentType->name) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->operatingFlight->equipmentType->acvCode) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->operatingFlight->cabin->class) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->operatingFlight->carrier->name) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->operatingFlight->carrier->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->sellingClass->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->marketingFlight->number) . "\n";
                                        echo ($instance->connections[0]->segments[0]->origin->name) . "\n";
                                        echo ($instance->connections[0]->segments[0]->origin->city->name) . "\n";
                                        echo ($instance->connections[0]->segments[0]->origin->city->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->origin->code) . "\n";
                                        echo ($instance->connections[0]->segments[0]->farebase->code) . "\n";
                                        echo ($instance->passengers[0]->id) . "\n";
                                        echo ($instance->passengers[0]->type) . "\n";
                                        echo ($instance->passengers[1]->id) . "\n";
                                        echo ($instance->passengers[1]->type) . "\n";
                                        echo ($instance->passengers[2]->id) . "\n";
                                        echo ($instance->passengers[2]->type) . "\n";
                                        echo ($instance->passengers[3]->id) . "\n";
                                        echo ($instance->passengers[3]->type) . "\n";
                                        echo ($instance->price->displayPrice) . "\n";
                                        echo ($instance->price->totalPrice) . "\n";
                                        echo ($instance->price->accuracy) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[0]->passengerType) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[0]->fare) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[0]->taxes) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[1]->passengerType) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[1]->fare) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[1]->taxes) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[2]->passengerType) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[2]->fare) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[2]->taxes) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[3]->passengerType) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[3]->fare) . "\n";
                                        echo ($instance->price->pricePerPassengerTypes[3]->taxes) . "\n";
                                        echo ($instance->price->flexibilityWaiver) . "\n";
                                        echo ($instance->price->displayType) . "\n";











                                        DB::table('flights_afklm')->insert([
                                            'uid' => uniqid(),
                                            's_no' => ++$j,
                                            'flight_date' => $date,
                                            'airline_code' => $currentAirline,
                                            'origin_name' => $name1,
                                            'origin_iata' => $iata1,
                                            'destination_name' => $name2,
                                            'destination_iata' => $iata2,
                                            'all_data' => serialize($instance),
                                            'source' => 'api.klm.com/opendata/flightoffers/available-offers',
                                            'created_at' => DB::raw('now()'),
                                            'updated_at' => DB::raw('now()')
                                        ]);
                                        echo 'Completed =  ' . $currentAirline . ' ' . $name1 . ' (' . $iata1 . ') & ' . $name2 . '(' . $iata2 . ')' . ' ' . $date . ' ' . "\n";
                                    }
                                }
                                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                            }
                        }
                    }
                }
            }
        }


    }
}
