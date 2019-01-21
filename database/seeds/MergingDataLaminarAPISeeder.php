<?php

use Illuminate\Database\Seeder;

class MergingDataLaminarAPISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 1; $i <= 250000; $i++) {
            if (DB::table('flights_test')->where('id', $i)->exists()) {
                $results = DB::table('flights_test')->where('id', $i)->get();

                foreach ($results as $value) {

                    if ($value) {

                        $value = unserialize(gzuncompress($value->all_data));

                        $flight_id = $iata_flight_number = $flight_status = $aircraft_code = $aircraft_registration = $airline =
                        $arrival_airport_scheduled = $arrival_airport_initial = $arrival_runway_time_initial_date = $arrival_runway_time_initial_time =
                        $arrival_runway_time_estimated_date = $arrival_runway_time_estimated_time = $callsign = $departure_airport_scheduled =
                        $departure_airport_initial = $departure_runway_time_initial_date = $departure_runway_time_initial_time =
                        $departure_runway_time_estimated_date = $departure_runway_time_estimated_time = $timestamp_processed_date =
                        $timestamp_processed_time = null;

//                        $flight = App\Flight::find($value->id);

                        if (isset($value->id)) {
                            $flight_id = $value->id;
                        }

                        if (isset($value->properties->airline)) {
                            $airline = $value->properties->airline;
                        }

                        if (isset($value->properties->arrival->aerodrome->scheduled)) {
                            $arrival_airport_scheduled = $value->properties->arrival->aerodrome->scheduled;
                        }
                        if (isset($value->properties->arrival->aerodrome->initial)) {
                            $arrival_airport_initial = $value->properties->arrival->aerodrome->initial;
                        }
                        if (isset($value->properties->arrival->aerodrome->actual)) {
                            $arrival_airport_initial = $value->properties->arrival->aerodrome->actual;
                        }
                        if (isset($value->properties->arrival->runwayTime->initial)) {
                            $array = explode("T", $value->properties->arrival->runwayTime->initial);
                            $arrival_runway_time_initial_date = $array[0];
                            $arrival_runway_time_initial_time = $array[1];
                        }
                        if (isset($value->properties->arrival->runwayTime->estimated)) {
                            $array = explode("T", $value->properties->arrival->runwayTime->estimated);
                            $arrival_runway_time_estimated_date = $array[0];
                            $arrival_runway_time_estimated_time = $array[1];
                        }

                        if (isset($value->properties->callsign)) {
                            $callsign = $value->properties->callsign;
                        }


                        if (isset($value->properties->departure->aerodrome->scheduled)) {
                            $departure_airport_scheduled = $value->properties->departure->aerodrome->scheduled;
                        }
                        if (isset($value->properties->arrival->aerodrome->initial)) {
                            $departure_airport_initial = $value->properties->arrival->aerodrome->initial;
                        }

                        if (isset($value->properties->departure->aerodrome->actual)) {
                            $departure_airport_initial = $value->properties->departure->aerodrome->actual;
                        }
                        if (isset($value->properties->departure->runwayTime->initial)) {
                            $array = explode("T", $value->properties->departure->runwayTime->initial);
                            $departure_runway_time_initial_date = $array[0];
                            $departure_runway_time_initial_time = $array[1];
                        }
                        if (isset($value->properties->departure->runwayTime->estimated)) {
                            $array = explode("T", $value->properties->departure->runwayTime->estimated);
                            $departure_runway_time_estimated_date = $array[0];
                            $departure_runway_time_estimated_time = $array[1];
                        }

                        if (isset($value->properties->flightStatus)) {
                            $flight_status = $value->properties->flightStatus;
                        }

                        if (isset($value->properties->iataFlightNumber)) {
                            $iata_flight_number = $value->properties->iataFlightNumber;
                        }
                        if (isset($value->properties->timestampProcessed)) {
                            $array = explode("T", $value->properties->timestampProcessed);
                            $timestamp_processed_date = $array[0];
                            $timestamp_processed_time = $array[1];
                        }

                        if (isset($value->properties->aircraftDescription->aircraftCode)) {
                            $aircraft_code = $value->properties->aircraftDescription->aircraftCode;
                        }
                        if (isset($value->properties->aircraftDescription->aircraftRegistration)) {
                            $aircraft_registration = $value->properties->aircraftDescription->aircraftRegistration;
                        }


                        if (DB::table('flights')->where('iata_flight_number', $value->properties->iataFlightNumber)->exists()) {

                            DB::table('flights')
                                ->where('iata_flight_number', $iata_flight_number)
                                ->update([
                                    'flight_id' => $flight_id,
                                    'iata_flight_number' => $iata_flight_number,
                                    'flight_status' => $flight_status,
                                    'aircraft_code' => $aircraft_code,
                                    'aircraft_registration' => $aircraft_registration,
                                    'airline' => $airline,
                                    'arrival_airport_scheduled' => $arrival_airport_scheduled,
                                    'arrival_airport_initial' => $arrival_airport_initial,
                                    'arrival_runway_time_initial_date' => $arrival_runway_time_initial_date,
                                    'arrival_runway_time_initial_time' => $arrival_runway_time_initial_time,
                                    'arrival_runway_time_estimated_date' => $arrival_runway_time_estimated_date,
                                    'arrival_runway_time_estimated_time' => $arrival_runway_time_estimated_time,
                                    'callsign' => $callsign,
                                    'departure_airport_scheduled' => $departure_airport_scheduled,
                                    'departure_airport_initial' => $departure_airport_initial,
                                    'departure_runway_time_initial_date' => $departure_runway_time_initial_date,
                                    'departure_runway_time_initial_time' => $departure_runway_time_initial_time,
                                    'departure_runway_time_estimated_date' => $departure_runway_time_estimated_date,
                                    'departure_runway_time_estimated_time' => $departure_runway_time_estimated_time,
                                    'timestamp_processed_date' => $timestamp_processed_date,
                                    'timestamp_processed_time' => $timestamp_processed_time,
                                    'updated_at' => DB::raw('now()')
                                ]);
                            echo 'updated :' . $i . "\n";
                        } else {

                            DB::table('flights')->insert([
                                'flight_id' => $flight_id,
                                'iata_flight_number' => $iata_flight_number,
                                'flight_status' => $flight_status,
                                'aircraft_code' => $aircraft_code,
                                'aircraft_registration' => $aircraft_registration,
                                'airline' => $airline,
                                'arrival_airport_scheduled' => $arrival_airport_scheduled,
                                'arrival_airport_initial' => $arrival_airport_initial,
                                'arrival_runway_time_initial_date' => $arrival_runway_time_initial_date,
                                'arrival_runway_time_initial_time' => $arrival_runway_time_initial_time,
                                'arrival_runway_time_estimated_date' => $arrival_runway_time_estimated_date,
                                'arrival_runway_time_estimated_time' => $arrival_runway_time_estimated_time,
                                'callsign' => $callsign,
                                'departure_airport_scheduled' => $departure_airport_scheduled,
                                'departure_airport_initial' => $departure_airport_initial,
                                'departure_runway_time_initial_date' => $departure_runway_time_initial_date,
                                'departure_runway_time_initial_time' => $departure_runway_time_initial_time,
                                'departure_runway_time_estimated_date' => $departure_runway_time_estimated_date,
                                'departure_runway_time_estimated_time' => $departure_runway_time_estimated_time,
                                'timestamp_processed_date' => $timestamp_processed_date,
                                'timestamp_processed_time' => $timestamp_processed_time,
                                'created_at' => DB::raw('now()'),
                                'updated_at' => DB::raw('now()')
                            ]);
                            echo 'inserted :' . $i . "\n";
                        }
                    }
                }


            }
        }


    }
}
