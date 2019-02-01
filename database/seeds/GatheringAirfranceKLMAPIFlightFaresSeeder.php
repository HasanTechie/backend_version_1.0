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

        $apiArray = Array(
            array("vxm2gshpczmyxx6fjenec9se", "aweinbacha"),
            array("4cxszedpevyer3h3fwja3wq3", "unbeatabil"),
            array("q2w2dqn6ehfbevv7pst7mwpr", "haasan"),
            array("khdkrw2pvvaqcs3pks96d5ve", "hasanabbax"),
            array("4kmnf3mnrk5ne5s53hk6xqvx", "maxbiocca"),
            array("gsj2x293zdacgezgf2hv5hhv", "sayyid"),
        );

        $url2 = 'https://api.klm.com/opendata/flightoffers/available-offers';

        $airports = [];

        $dest_cities = ['Berlin', 'Frankfurt', 'Hamburg', 'Munich', 'Cologne'];

        $cities = [
            'London',
            'Paris',
            'Munich',
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


        // Start date
        $date = '2019-05-03';
        //$end_date = '2019-02-07';
        $end_date = '2019-07-31';

        // Set timezone
        date_default_timezone_set('UTC');

        if ($result1 = DB::table('flights_afklm')->orderBy('s_no', 'desc')->first()) {
            $j = $result1->s_no;
        } else {
            $j = 0;
        }
        $m = 0;

        $requestCount = 0;

        while (strtotime($date) <= strtotime($end_date)) {

//            foreach ($cities as $city1) { //from here
//                foreach ($dest_cities as $city2) { //to here


//                    $airports[0] = DB::table('airports')->select(['iata', 'name'])->where('city', '=', $city1)->distinct()->get();
            $airports[0] = DB::table('flights_afklm')->select(['origin_airport_initial'])->distinct()->get();

//                    $airports[1] = DB::table('airports')->select(['iata', 'name'])->where('city', '=', $city2)->distinct()->get();
            $airports[1] = DB::table('flights_afklm')->select(['destination_airport_final'])->distinct()->get();

            $airline[0] = 'AF';
            $airline[1] = 'KL';

            for ($k = 0; $k <= 1; $k++) {

                $currentAirline = $airline[$k];

                foreach ($airports[0] as $airport1) {
                    foreach ($airports[1] as $airport2) {

                        $iata1 = $airport1->origin_airport_initial;
                        $iata2 = $airport2->destination_airport_final;

                        $continue = 1;

                        /*
                                $name1 = $airport1->name;
                                $name2 = $airport2->name;

                                $result2 = DB::table('flights_afklm')->where([
                                    ['ignore_oiata', '=', $iata1],
                                    ['ignore_diata', '=', $iata2]
                                ])->orderBy('s_no', 'desc')->first();


                                if (isset($result2)) {



                                    //sdate = 2jun depdate = 4jun -> skip
                                    //sdate = 5jun depdate = 4jun -> continue

                                    //edate = 8 depdate = 4 ->continue
                                    //edate = 3 depdate = 4 ->skip

                                    if ((strtotime($result2->departure_date) < strtotime($end_date)) && $result2->ignore_oiata == $iata1 && $result2->ignore_diata) {


                                        if ((strtotime($result2->departure_date) < strtotime($date)) && $result2->ignore_oiata == $iata1 && $result2->ignore_diata) {

                                            $iata1 = $result2->ignore_oiata;
                                            $iata2 = $result2->ignore_diata;


                                            $date = $result2->departure_date;

//                                                $date = date("Y-m-d", strtotime("+2 day", strtotime($date)));

                                            $continue = 1;
                                        } else {
                                            $continue = 1;
                                        }
                                    } else {
                                        $continue = 1;
                                    }
                                } else {
                                    $continue = 1;
                                }
                        */

                        if ($continue == 1) {
                            if (isset($iata1) && isset($iata2)) {
                                $headers = [
                                    'accept' => 'application/hal+json;profile=com.afklm.b2c.flightoffers.available-offers.v1;charset=utf8',
                                    'accept-language' => 'en-US',
                                    'afkl-travel-country' => 'DE',
                                    'afkl-travel-host' => $currentAirline,
                                    'api-key' => $apiArray[$m][0],
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
                                            echo "Overate Reached" . "\n";
                                            $m++;
                                        }
                                        $incomplete = ++$requestCount . ' Incompleted =  ' . $currentAirline . ' ' . ' (' . $iata1 . ') & ' . '(' . $iata2 . ')' . ' ' . $date . ' ' . "\n";
                                        $response = '';
                                        echo $incomplete;
                                    }
                                }

                                if (!empty($response)) {

                                    foreach ($response->flightProducts as $instance) {

                                        $flightArray = [];
                                        $fidsegments = '';
                                        $totalNumberOfFlights = 0;

                                        $flightCarriers = '';
                                        $flightNumbers = '';
                                        $l = 0;

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

                                            $fidsegments .= $segment->marketingFlight->carrier->code . '&' . $segment->marketingFlight->number . '&' . $segment->origin->code
                                                . '&' . $segment->destination->code . '&' . $departureDateTime . '&' . $arrivalDateTime;

                                            $flightArray[$l++] = serialize($segment);

                                            $flightCarriers .= $segment->marketingFlight->carrier->code;
                                            $flightNumbers .= $segment->marketingFlight->number;
                                            $totalNumberOfFlights++;
                                        }

                                        $fid = $fidsegments . $instance->connections[0]->duration . '&' . $instance->price->totalPrice . '&' .
                                            $instance->connections[0]->numberOfSeatsAvailable . '&' . $instance->price->displayPrice;

                                        if (!(DB::table('flights_afklm')->where('fid', '=', $fid)->exists())) {

                                            DB::table('flights_afklm')->insert([
                                                'uid' => $id,
                                                's_no' => ++$j,
                                                'fid' => $fid,
                                                'flights_duration' => isset($instance->connections[0]->duration) ? ($instance->connections[0]->duration) : 0,
                                                'number_of_flights' => $totalNumberOfFlights,
                                                'number_of_seats_available' => isset($instance->connections[0]->numberOfSeatsAvailable) ? ($instance->connections[0]->numberOfSeatsAvailable) : null,

                                                'flights_data' => serialize($flightArray),

                                                'display_price' => isset($instance->price->displayPrice) ? ($instance->price->displayPrice) : 0,
                                                'total_price' => isset($instance->price->totalPrice) ? ($instance->price->totalPrice) : 0,
                                                'accuracy' => isset($instance->price->accuracy) ? ($instance->price->accuracy) : 0,
                                                'passenger_1_type' => isset($instance->price->pricePerPassengerTypes[0]->passengerType) ? ($instance->price->pricePerPassengerTypes[0]->passengerType) : null,
                                                'passenger_1_fare' => isset($instance->price->pricePerPassengerTypes[0]->fare) ? ($instance->price->pricePerPassengerTypes[0]->fare) : 0,
                                                'passenger_1_taxes' => isset($instance->price->pricePerPassengerTypes[0]->taxes) ? ($instance->price->pricePerPassengerTypes[0]->taxes) : 0,
                                                'passenger_2_type' => isset($instance->price->pricePerPassengerTypes[1]->passengerType) ? ($instance->price->pricePerPassengerTypes[1]->passengerType) : null,
                                                'passenger_2_fare' => isset($instance->price->pricePerPassengerTypes[1]->fare) ? ($instance->price->pricePerPassengerTypes[1]->fare) : 0,
                                                'passenger_2_taxes' => isset($instance->price->pricePerPassengerTypes[1]->taxes) ? ($instance->price->pricePerPassengerTypes[1]->taxes) : 0,
                                                'passenger_3_type' => isset($instance->price->pricePerPassengerTypes[2]->passengerType) ? ($instance->price->pricePerPassengerTypes[2]->passengerType) : null,
                                                'passenger_3_fare' => isset($instance->price->pricePerPassengerTypes[2]->fare) ? ($instance->price->pricePerPassengerTypes[2]->fare) : 0,
                                                'passenger_3_taxes' => isset($instance->price->pricePerPassengerTypes[2]->taxes) ? ($instance->price->pricePerPassengerTypes[2]->taxes) : 0,
                                                'passenger_4_type' => isset($instance->price->pricePerPassengerTypes[3]->passengerType) ? ($instance->price->pricePerPassengerTypes[3]->passengerType) : null,
                                                'passenger_4_fare' => isset($instance->price->pricePerPassengerTypes[3]->fare) ? ($instance->price->pricePerPassengerTypes[3]->fare) : 0,
                                                'passenger_4_taxes' => isset($instance->price->pricePerPassengerTypes[3]->taxes) ? ($instance->price->pricePerPassengerTypes[3]->taxes) : 0,
                                                'flexibility_waiver' => isset($instance->price->flexibilityWaiver) ? ($instance->price->flexibilityWaiver) : false,
                                                'currency' => isset($instance->price->currency) ? ($instance->price->currency) : null,
                                                'display_type' => isset($instance->price->displayType) ? ($instance->price->displayType) : null,

                                                'origin_airport_initial' => $iata1,
                                                'destination_airport_final' => $iata2,
                                                'source' => 'api.klm.com/opendata/flightoffers/available-offers',
                                                'created_at' => DB::raw('now()'),
                                                'updated_at' => DB::raw('now()')
                                            ]);

                                            $response = '';
                                            echo ' Completed =' . $j . ' ' . $currentAirline . $flightNumbers . $flightCarriers . ' ' . ' (' . $iata1 . ') & ' . '(' . $iata2 . ')' . ' ' . $date . '   ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                                        } else {
                                            echo ' Existed =' . $j . ' ' . $currentAirline . $flightNumbers . $flightCarriers . ' ' . ' (' . $iata1 . ') & ' . '(' . $iata2 . ')' . ' ' . $date . '   ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                                        }
                                    }

                                    echo ++$requestCount . ' $reponse not empty' . "\n";
                                } else {
                                    if (empty($incomplete)) {
                                        echo ++$requestCount . ' NullResponse =  ' . $currentAirline . ' ' . ' (' . $iata1 . ') & ' . '(' . $iata2 . ')' . ' ' . $date . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                                    }
                                }
                            }
                        }
                    }
                }
            }
//                }
//            }

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
