<?php

use Illuminate\Database\Seeder;

use App\Flight;

class CompletedFlightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $titles = DB::table('airports')->pluck('ICAO');

        foreach ($titles as $title) {

            $url = "https://api.laminardata.aero/v1/aerodromes/$title/departures?user_key=5a183c1f789682da267a20a54ca91197&status=completed";


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 300000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Accept: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                if (isset($response)) {
                    $json = json_decode($response);
                }
            }

            if (isset($json->features)) {
                if (is_array($json->features)) {
                    foreach ($json->features as $value) {

                        if ($value) {
                            $flight = new Flight();

                            if (isset($value->id)) {
                                $flight->flight_id = $value->id;
                            }
                            if (isset($value->type)) {
                                $flight->type = $value->type;
                            }

                            if (isset($value->geometry)) {
                                if (is_array($value->geometry) || is_object($value->geometry)) {
                                    if (isset($value->geometry->type)) {
                                        $flight->geometry_type = $value->geometry->type;
                                    }
                                    if (isset($value->geometry->coordinates)) {
                                        if (is_array($value->geometry->coordinates) || is_object($value->geometry->coordinates)) {
                                            $flight->geometry_coordinates = serialize($value->geometry->coordinates);
                                        }
                                    }
                                    $flight->geometry = 'Available';
                                } else {
                                    $flight->geometry = $value->geometry;
                                }
                            }

                            if (isset($value->properties->airline)) {
                                $flight->airline = $value->properties->airline;
                            }

                            if (isset($value->properties->arrival->aerodrome->scheduled)) {
                                $flight->arrival_airport_scheduled = $value->properties->arrival->aerodrome->scheduled;
                            }
                            if (isset($value->properties->arrival->aerodrome->initial)) {
                                $flight->arrival_airport_actual = $value->properties->arrival->aerodrome->initial;
                            }
                            if (isset($value->properties->arrival->aerodrome->actual)) {
                                $flight->arrival_airport_actual = $value->properties->arrival->aerodrome->actual;
                            }
                            if (isset($value->properties->arrival->runwayTime->initial)) {
                                $array = explode("T", $value->properties->arrival->runwayTime->initial);
                                $flight->arrival_runway_time_initial_date = $array[0];
                                $flight->arrival_runway_time_initial_time = $array[1];
                            }
                            if (isset($value->properties->arrival->runwayTime->estimated)) {
                                $array = explode("T", $value->properties->arrival->runwayTime->estimated);
                                $flight->arrival_runway_time_estimated_date = $array[0];
                                $flight->arrival_runway_time_estimated_time = $array[1];
                            }

                            if (isset($value->properties->callsign)) {
                                $flight->callsign = $value->properties->callsign;
                            }

                            if (isset($value->properties->departure->gateTime->estimated)) {
                                $array = explode("T", $value->properties->departure->gateTime->estimated);
                                $flight->gate_time_date = $array[0];
                                $flight->gate_time_time = $array[1];
                            }

                            if (isset($value->properties->departure->aerodrome->scheduled)) {
                                $flight->departure_airport_scheduled = $value->properties->departure->aerodrome->scheduled;
                            }
                            if (isset($value->properties->arrival->aerodrome->initial)) {
                                $flight->departure_airport_actual = $value->properties->arrival->aerodrome->initial;
                            }

                            if (isset($value->properties->departure->aerodrome->actual)) {
                                $flight->departure_airport_actual = $value->properties->departure->aerodrome->actual;
                            }
                            if (isset($value->properties->departure->runwayTime->initial)) {
                                $array = explode("T", $value->properties->departure->runwayTime->initial);
                                $flight->departure_runway_time_initial_date = $array[0];
                                $flight->departure_runway_time_initial_time = $array[1];
                            }
                            if (isset($value->properties->departure->runwayTime->estimated)) {
                                $array = explode("T", $value->properties->departure->runwayTime->estimated);
                                $flight->departure_runway_time_estimated_date = $array[0];
                                $flight->departure_runway_time_estimated_time = $array[1];
                            }

                            if (isset($value->properties->flightStatus)) {
                                $flight->flight_status = $value->properties->flightStatus;
                            }
                            if (isset($value->properties->positionReport)) {
                                if (is_array($value->properties->positionReport) || is_object($value->properties->positionReport)) {
                                    $flight->position_report = serialize($value->properties->positionReport);
                                }
                            }
                            if (isset($value->properties->iataFlightNumber)) {
                                $flight->iata_flight_number = $value->properties->iataFlightNumber;
                            }
                            if (isset($value->properties->timestampProcessed)) {
                                $array = explode("T", $value->properties->timestampProcessed);
                                $flight->timestamp_processed_date = $array[0];
                                $flight->timestamp_processed_time = $array[1];
                            }

                            if (isset($value->properties->aircraftDescription->aircraftCode)) {
                                $flight->aircraft_code = $value->properties->aircraftDescription->aircraftCode;
                            }
                            if (isset($value->properties->aircraftDescription->aircraftRegistration)) {
                                $flight->aircraft_registration = $value->properties->aircraftDescription->aircraftRegistration;
                            }
                            $flight->save();

                        }
                    }
                }
            }
        }
    }
}
