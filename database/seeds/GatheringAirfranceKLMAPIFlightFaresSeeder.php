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

                                $iata1 = 'HAM';
                                $iata2 = 'BCN';

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


                                        foreach ($instance->connections[0]->segments as $segment) {


                                            DB::table('flights_afklm')->insert([
                                                'uid' => uniqid(),
                                                's_no' => ++$j,

                                                'flight_number' => ($segment->marketingFlight->number),
                                                'flight_duration' => ($instance->connections[0]->duration),
                                                'number_of_seats_available' => ($instance->connections[0]->numberOfSeatsAvailable),

                                                'arrival_date_time' => ($segment->arrivalDateTime),
                                                'departure_date_time' => ($segment->departureDateTime),
                                                'destination_airport' => ($segment->destination->name),
                                                'destination_city' => ($segment->destination->city->name),
                                                'destination_city_code' => ($segment->destination->city->code),
                                                'destination_airport_iata' => ($segment->destination->code),
                                                'carrier_name' => ($segment->marketingFlight->carrier->name),
                                                'carrier_code' => ($segment->marketingFlight->carrier->code),
                                                'number_of_stops' => ($segment->marketingFlight->numberOfStops),
                                                'equipmenttype_code' => ($segment->marketingFlight->operatingFlight->equipmentType->code),
                                                'equipmenttype_name' => ($segment->marketingFlight->operatingFlight->equipmentType->name),
                                                'equipmenttype_acvCode' => ($segment->marketingFlight->operatingFlight->equipmentType->acvCode),
                                                'cabin_name' => ($segment->marketingFlight->operatingFlight->cabin->class),
                                                'flight_carrier_name' => ($segment->marketingFlight->operatingFlight->carrier->name),
                                                'flight_carrier_code' => ($segment->marketingFlight->operatingFlight->carrier->code),
                                                'selling_class_code' => ($segment->marketingFlight->sellingClass->code),
                                                'origin_airport' => ($segment->origin->name),
                                                'origin_city' => ($segment->origin->city->name),
                                                'origin_city_code' => ($segment->origin->city->code),
                                                'origin_airport_iata' => ($segment->origin->code),
                                                'farebase_code' => ($segment->farebase->code),
                                                'transfer_time' => ($segment->transferTime),

                                                'total_displayPrice' => ($instance->price->displayPrice),
                                                'total_totalPrice' => ($instance->price->totalPrice),
                                                'total_accuracy' => ($instance->price->accuracy),
                                                'total_passenger_1_type' => ($instance->price->pricePerPassengerTypes[0]->passengerType),
                                                'total_passenger_1_fare' => ($instance->price->pricePerPassengerTypes[0]->fare),
                                                'total_passenger_1_taxes' => ($instance->price->pricePerPassengerTypes[0]->taxes),
                                                'total_passenger_2_type' => ($instance->price->pricePerPassengerTypes[1]->passengerType),
                                                'total_passenger_2_fare' => ($instance->price->pricePerPassengerTypes[1]->fare),
                                                'total_passenger_2_taxes' => ($instance->price->pricePerPassengerTypes[1]->taxes),
                                                'total_passenger_3_type' => ($instance->price->pricePerPassengerTypes[2]->passengerType),
                                                'total_passenger_3_fare' => ($instance->price->pricePerPassengerTypes[2]->fare),
                                                'total_passenger_3_taxes' => ($instance->price->pricePerPassengerTypes[2]->taxes),
                                                'total_passenger_4_type' => ($instance->price->pricePerPassengerTypes[3]->passengerType),
                                                'total_passenger_4_fare' => ($instance->price->pricePerPassengerTypes[3]->fare),
                                                'total_passenger_4_taxes' => ($instance->price->pricePerPassengerTypes[3]->taxes),
                                                'flexibility_waiver' => ($instance->price->flexibilityWaiver),
                                                'currency' => ($instance->price->currency),
                                                'display_type' => ($instance->price->displayType),

                                                'source' => 'api.klm.com/opendata/flightoffers/available-offers',
                                                'created_at' => DB::raw('now()'),
                                                'updated_at' => DB::raw('now()')
                                            ]);
                                            echo 'Completed =  ' . $currentAirline . ' ' . $name1 . ' (' . $iata1 . ') & ' . $name2 . '(' . $iata2 . ')' . ' ' . $date . ' ' . "\n";
                                        }
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


/*
                                            echo ($instance->connections[0]->duration) . "\n";
                                            echo ($instance->connections[0]->numberOfSeatsAvailable) . "\n";

                                            echo ($segment->arrivalDateTime) . "\n";
                                            echo ($segment->departureDateTime) . "\n";
                                            echo ($segment->destination->name) . "\n";
                                            echo ($segment->destination->city->name) . "\n";
                                            echo ($segment->destination->city->code) . "\n";
                                            echo ($segment->destination->code) . "\n";
                                            echo ($segment->marketingFlight->carrier->name) . "\n";
                                            echo ($segment->marketingFlight->carrier->code) . "\n";
                                            echo ($segment->marketingFlight->numberOfStops) . "\n";
                                            echo ($segment->marketingFlight->operatingFlight->equipmentType->code) . "\n";
                                            echo ($segment->marketingFlight->operatingFlight->equipmentType->name) . "\n";
                                            echo ($segment->marketingFlight->operatingFlight->equipmentType->acvCode) . "\n";
                                            echo ($segment->marketingFlight->operatingFlight->cabin->class) . "\n";
                                            echo ($segment->marketingFlight->operatingFlight->carrier->name) . "\n";
                                            echo ($segment->marketingFlight->operatingFlight->carrier->code) . "\n";
                                            echo ($segment->marketingFlight->sellingClass->code) . "\n";
                                            echo ($segment->marketingFlight->number) . "\n";
                                            echo ($segment->origin->name) . "\n";
                                            echo ($segment->origin->city->name) . "\n";
                                            echo ($segment->origin->city->code) . "\n";
                                            echo ($segment->origin->code) . "\n";
                                            echo ($segment->farebase->code) . "\n";

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
 */
