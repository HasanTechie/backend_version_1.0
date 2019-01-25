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
//        $apiKey = '4kmnf3mnrk5ne5s53hk6xqvx'; max biocca's
        $apiKey = 'khdkrw2pvvaqcs3pks96d5ve';

        $url2 = 'https://api.klm.com/opendata/flightoffers/available-offers';

        $airports = [];

        $cities = ['Frankfurt', 'Berlin', 'Cologne', 'Hamburg', 'Munich'];

        $dest_cities = [
            'Munich',
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


        $result = DB::table('flights_afklm')->orderBy('s_no', 'desc')->first();

        if (isset($result)) {
            $j = $result->s_no;
        } else {
            $j = 0;
        }


        //Our YYYY-MM-DD date string.

//Get the day of the week using PHP's date function.


//Print out the day that our date fell on.

        // Start date
        $date = '2019-02-01';
        //$end_date = '2019-07-31';
        $end_date = '2019-02-03';

        // Set timezone
        date_default_timezone_set('UTC');

        while (strtotime($date) <= strtotime($end_date)) {

            foreach ($cities as $city1) { //from here
                foreach ($dest_cities as $city2) { //to here


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


                                if ($result1 = DB::table('flights_afklm')->orderBy('s_no', 'desc')->first()) {
                                    if ($result2 = DB::table('flights_afklm')->orderBy('s_no', 'desc')->skip(1)->take(1)->first()) {

                                        $code = DB::table('flights_afklm')->where([
                                            ['origin_airport_iata', '=', $iata1],
                                            ['destination_airport_iata', '=', $iata2]
                                        ])->exists();

                                        echo $iata1 . $iata2;
                                        dd($code);
                                        if (1 == 1) {


                                            //date = 2jun depdate = 4jun -> skip
                                            //date = 5jun depdate = 4jun -> continue

                                            //DB::table('flights_afklm')->orderBy('s_no', 'desc')->skip(1)->take(1)->first()

                                            if (strtotime($result1->departure_date) < strtotime($date)) {

                                                $date = $result1->departure_date;

                                                $iata1 = $result2->origin_airport_iata;
                                                $iata2 = $result1->destination_airport_iata;

                                                $date = date("Y-m-d", strtotime("+2 day", strtotime($date)));


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
                                                    $response = '';
                                                    $response = $client->request('POST', $url2, [
                                                        'json' => json_decode($body),
                                                        'headers' => $headers
                                                    ]);

                                                    $response = json_decode($response->getBody());


                                                } catch (\Exception $ex) {
                                                    if (!empty($ex)) {

                                                        if (stripos($ex->getMessage(), "Developer Over Rate") !== false) {
                                                            dd("Overate Reached");
                                                        }
                                                        echo 'Incompleted =  ' . $currentAirline . ' ' . $name1 . ' (' . $iata1 . ') & ' . $name2 . '(' . $iata2 . ')' . ' ' . $date . ' ' . "\n";
                                                        $response = '';
                                                    }
                                                }


                                                if (!empty($response)) {

                                                    foreach ($response->flightProducts as $instance) {


                                                        foreach ($instance->connections[0]->segments as $segment) {

                                                            $id = uniqid();

                                                            $arrivalDate = $arrivalTime = $arrivalDayOfWeek = null;
                                                            if (isset($segment->arrivalDateTime)) {

                                                                $arrivalDateTime = $segment->arrivalDateTime;
                                                                $arrivalArray = explode("T", $arrivalDateTime);
                                                                $arrivalDate = $arrivalArray[0];
                                                                $arrivalTime = $arrivalArray[1];
                                                                $arrivalDayOfWeek = date("l", strtotime($arrivalDate));
                                                            }

                                                            $departureDate = $departureTime = $departureDayOfWeek = null;
                                                            if (isset($segment->departureDateTime)) {

                                                                $departureDateTime = $segment->departureDateTime;
                                                                $departureArray = explode("T", $departureDateTime);
                                                                $departureDate = $departureArray[0];
                                                                $departureTime = $departureArray[1];
                                                                $departureDayOfWeek = date("l", strtotime($departureDate));
                                                            }


                                                            $fid = $segment->marketingFlight->carrier->code . '&' . $segment->marketingFlight->number . '&' . $segment->origin->code . '&' . $segment->destination->code . '&' . $departureTime . '&' . $arrivalDateTime . '&' . $instance->connections[0]->duration;
                                                            if (!(DB::table('flights_afklm')->where('fid', '=', $fid)->exists())) {

                                                                DB::table('flights_afklm')->insert([
                                                                    'uid' => $id,
                                                                    's_no' => ++$j,
                                                                    'fid' => $fid,
                                                                    'flight_number' => isset($segment->marketingFlight->number) ? ($segment->marketingFlight->number) : 0,
                                                                    'total_flight_duration' => isset($instance->connections[0]->duration) ? ($instance->connections[0]->duration) : 0,
                                                                    'total_number_of_seats_available' => isset($instance->connections[0]->numberOfSeatsAvailable) ? ($instance->connections[0]->numberOfSeatsAvailable) : null,

                                                                    'arrival_date' => $arrivalDate,
                                                                    'arrival_time' => $arrivalTime,
                                                                    'arrival_day' => $arrivalDayOfWeek,
                                                                    'departure_date' => $departureDate,
                                                                    'departure_time' => $departureTime,
                                                                    'departure_day' => $departureDayOfWeek,
                                                                    'destination_airport' => isset($segment->destination->name) ? ($segment->destination->name) : null,
                                                                    'destination_city' => isset($segment->destination->city->name) ? ($segment->destination->city->name) : null,
                                                                    'destination_city_code' => isset($segment->destination->city->code) ? ($segment->destination->city->code) : null,
                                                                    'destination_airport_iata' => isset($segment->destination->code) ? ($segment->destination->code) : null,
                                                                    'carrier_name' => isset($segment->marketingFlight->carrier->name) ? ($segment->marketingFlight->carrier->name) : null,
                                                                    'carrier_code' => isset($segment->marketingFlight->carrier->code) ? ($segment->marketingFlight->carrier->code) : null,
                                                                    'number_of_stops' => isset($segment->marketingFlight->numberOfStops) ? ($segment->marketingFlight->numberOfStops) : null,
                                                                    'equipmenttype_code' => isset($segment->marketingFlight->operatingFlight->equipmentType->code) ? ($segment->marketingFlight->operatingFlight->equipmentType->code) : null,
                                                                    'equipmenttype_name' => isset($segment->marketingFlight->operatingFlight->equipmentType->name) ? ($segment->marketingFlight->operatingFlight->equipmentType->name) : null,
                                                                    'equipmenttype_acvCode' => isset($segment->marketingFlight->operatingFlight->equipmentType->acvCode) ? ($segment->marketingFlight->operatingFlight->equipmentType->acvCode) : null,
                                                                    'cabin_class' => isset($segment->marketingFlight->operatingFlight->cabin->class) ? ($segment->marketingFlight->operatingFlight->cabin->class) : null,
                                                                    'flight_carrier_name' => isset($segment->marketingFlight->operatingFlight->carrier->name) ? ($segment->marketingFlight->operatingFlight->carrier->name) : null,
                                                                    'flight_carrier_code' => isset($segment->marketingFlight->operatingFlight->carrier->code) ? ($segment->marketingFlight->operatingFlight->carrier->code) : null,
                                                                    'selling_class_code' => isset($segment->marketingFlight->sellingClass->code) ? ($segment->marketingFlight->sellingClass->code) : null,
                                                                    'origin_airport' => isset($segment->origin->name) ? ($segment->origin->name) : null,
                                                                    'origin_city' => isset($segment->origin->city->name) ? ($segment->origin->city->name) : null,
                                                                    'origin_city_code' => isset($segment->origin->city->code) ? ($segment->origin->city->code) : null,
                                                                    'origin_airport_iata' => isset($segment->origin->code) ? ($segment->origin->code) : null,
                                                                    'farebase_code' => isset($segment->farebase->code) ? ($segment->farebase->code) : null,
                                                                    'transfer_time' => isset($segment->transferTime) ? ($segment->transferTime) : 0,

                                                                    'total_displayPrice' => isset($instance->price->displayPrice) ? ($instance->price->displayPrice) : 0,
                                                                    'total_totalPrice' => isset($instance->price->totalPrice) ? ($instance->price->totalPrice) : 0,
                                                                    'total_accuracy' => isset($instance->price->accuracy) ? ($instance->price->accuracy) : 0,
                                                                    'total_passenger_1_type' => isset($instance->price->pricePerPassengerTypes[0]->passengerType) ? ($instance->price->pricePerPassengerTypes[0]->passengerType) : null,
                                                                    'total_passenger_1_fare' => isset($instance->price->pricePerPassengerTypes[0]->fare) ? ($instance->price->pricePerPassengerTypes[0]->fare) : 0,
                                                                    'total_passenger_1_taxes' => isset($instance->price->pricePerPassengerTypes[0]->taxes) ? ($instance->price->pricePerPassengerTypes[0]->taxes) : 0,
                                                                    'total_passenger_2_type' => isset($instance->price->pricePerPassengerTypes[1]->passengerType) ? ($instance->price->pricePerPassengerTypes[1]->passengerType) : null,
                                                                    'total_passenger_2_fare' => isset($instance->price->pricePerPassengerTypes[1]->fare) ? ($instance->price->pricePerPassengerTypes[1]->fare) : 0,
                                                                    'total_passenger_2_taxes' => isset($instance->price->pricePerPassengerTypes[1]->taxes) ? ($instance->price->pricePerPassengerTypes[1]->taxes) : 0,
                                                                    'total_passenger_3_type' => isset($instance->price->pricePerPassengerTypes[2]->passengerType) ? ($instance->price->pricePerPassengerTypes[2]->passengerType) : null,
                                                                    'total_passenger_3_fare' => isset($instance->price->pricePerPassengerTypes[2]->fare) ? ($instance->price->pricePerPassengerTypes[2]->fare) : 0,
                                                                    'total_passenger_3_taxes' => isset($instance->price->pricePerPassengerTypes[2]->taxes) ? ($instance->price->pricePerPassengerTypes[2]->taxes) : 0,
                                                                    'total_passenger_4_type' => isset($instance->price->pricePerPassengerTypes[3]->passengerType) ? ($instance->price->pricePerPassengerTypes[3]->passengerType) : null,
                                                                    'total_passenger_4_fare' => isset($instance->price->pricePerPassengerTypes[3]->fare) ? ($instance->price->pricePerPassengerTypes[3]->fare) : 0,
                                                                    'total_passenger_4_taxes' => isset($instance->price->pricePerPassengerTypes[3]->taxes) ? ($instance->price->pricePerPassengerTypes[3]->taxes) : 0,
                                                                    'flexibility_waiver' => isset($instance->price->flexibilityWaiver) ? ($instance->price->flexibilityWaiver) : false,
                                                                    'currency' => isset($instance->price->currency) ? ($instance->price->currency) : null,
                                                                    'display_type' => isset($instance->price->displayType) ? ($instance->price->displayType) : null,

                                                                    'source' => 'api.klm.com/opendata/flightoffers/available-offers',
                                                                    'created_at' => DB::raw('now()'),
                                                                    'updated_at' => DB::raw('now()')
                                                                ]);
                                                                echo 'Completed =  ' . $j . ' ' . $currentAirline . ' ' . $id . ' (' . $iata1 . ') -> ' . '(' . $iata2 . ')' . ' ' . $date . '   ' . $fid . "\n";
                                                            } else {
                                                                echo 'it existed' . "\n";
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


                    }
                }
            }
            $date = date("Y-m-d", strtotime("+2 day", strtotime($date)));
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
