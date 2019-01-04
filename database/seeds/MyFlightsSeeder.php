<?php

use Illuminate\Database\Seeder;

use App\Flight;

class MyFlightsSeeder extends Seeder
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


            $url = "https://api.laminardata.aero/v1/aerodromes/$title/departures?user_key=5a183c1f789682da267a20a54ca91197&status=scheduled";
//            dd($url);
//        $response = Unirest\Request::get($url);


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
                $json = json_decode($response);
            }
//            dd($json->features);
//            if (isset($json->features)) {dd($json->features);}

            if (isset($json->features)) {
                foreach ($json->features as $value) {

                    $flight = new Flight();

                    if (isset($value->id)) {
                        $flight->flight_id = $value->id;
                    }
                    if (isset($value->type)) {
                        $flight->type = $value->type;
                    }
                    if (isset($value->geometry)) {
                        $flight->geometry = $value->geometry;
                    }
                    if (isset($value->properties->airline)) {
                        $flight->airline = $value->properties->airline;
                    }
                    if (isset($value->properties->arrival->aerodrome->scheduled)) {
                        $flight->arrival_airport_scheduled = $value->properties->arrival->aerodrome->scheduled;
                    }
                    if (isset($value->properties->arrival->aerodrome->actual)) {
                        $flight->arrival_airport_actual = $value->properties->arrival->aerodrome->actual;
                    }
                    if (isset($value->properties->arrival->runwayTime->initial)) {
                        $flight->arrival_runway_time_initial = $value->properties->arrival->runwayTime->initial;
                    }
                    if (isset($value->properties->arrival->runwayTime->estimated)) {
                        $flight->arrival_runway_time_estimated = $value->properties->arrival->runwayTime->estimated;
                    }
                    if (isset($value->properties->departure->aerodrome->scheduled)) {
                        $flight->departure_airport_scheduled = $value->properties->departure->aerodrome->scheduled;
                    }
                    if (isset($value->properties->departure->aerodrome->actual)) {
                        $flight->departure_airport_actual = $value->properties->departure->aerodrome->actual;
                    }
                    if (isset($value->properties->departure->runwayTime->initial)) {
                        $flight->departure_runway_time_initial = $value->properties->departure->runwayTime->initial;
                    }
                    if (isset($value->properties->departure->runwayTime->estimated)) {
                        $flight->departure_runway_time_estimated = $value->properties->departure->runwayTime->estimated;
                    }
                    if (isset($value->properties->flightStatus)) {
                        $flight->flight_status = $value->properties->flightStatus;
                    }
                    if (isset($value->properties->iataFlightNumber)) {
                        $flight->iata_flight_number = $value->properties->iataFlightNumber;
                    }
                    if (isset($value->properties->timestampProcessed)) {
                        $flight->timestamp_processed = $value->properties->timestampProcessed;
                    }
                    if (isset($value->properties->aircraftDescription->aircraftCode)) {
                        $flight->aircraft_code = $value->properties->aircraftDescription->aircraftCode;
                    }

                    $flight->save();
                }
            }
        }
    }
}
