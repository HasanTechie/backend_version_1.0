<?php

namespace App\Http\Controllers;

use App\Flight;
use Illuminate\Http\Request;

use Storage;
use Unirest;

use DB;

use Orchestra\Parser\Xml\Facade as XmlParser;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $flights = Flight::inRandomOrder()->paginate(25); //limit to 2000
        return view('flights.index', compact('flights'));

    }

    public function current()
    {
        $flights = Flight::inRandomOrder()->where('flight_status', 'Airborne')->paginate(25); //limit to 2000

//        $flights = Flight::inRandomOrder()->paginate(25); //limit to 2000
        return view('flights.current', compact('flights'));
    }

//    public function test()
//    {
//        $titles = DB::table('airports')->pluck('ICAO');
//
//
//        foreach ($titles as $title) {
//
//
//            $url = "https://api.laminardata.aero/v1/aerodromes/$title/departures?user_key=5a183c1f789682da267a20a54ca91197&status=airborne";
//
//            dd($url);
////        $response = Unirest\Request::get($url);
//
//
//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => $url,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => "",
//                CURLOPT_TIMEOUT => 30000,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => "GET",
//                CURLOPT_HTTPHEADER => array(
//                    // Set Here Your Requesred Headers
//                    'Accept: application/json',
//                ),
//            ));
//            $response = curl_exec($curl);
//            $err = curl_error($curl);
//            curl_close($curl);
//
//            if ($err) {
//                echo "cURL Error #:" . $err;
//            } else {
//                $json = json_decode($response);
//            }
//
//            foreach ($json->features as $value) {
//
//                $flight = new Flight();
//
//                if (isset($value->id)) {
//                    $flight->flight_id = $value->id;
//                }
//                if (isset($value->type)) {
//                    $flight->type = $value->type;
//                }
//                if (isset($value->geometry)) {
//                    $flight->geometry = $value->geometry;
//                }
//                if (isset($value->properties->airline)) {
//                    $flight->airline = $value->properties->airline;
//                }
//                if (isset($value->properties->arrival->aerodrome->scheduled)) {
//                    $flight->arrival_airport_scheduled = $value->properties->arrival->aerodrome->scheduled;
//                }
//                if (isset($value->properties->arrival->aerodrome->actual)) {
//                    $flight->arrival_airport_actual = $value->properties->arrival->aerodrome->actual;
//                }
//                if (isset($value->properties->arrival->runwayTime->initial)) {
//                    $flight->arrival_runway_time_initial = $value->properties->arrival->runwayTime->initial;
//                }
//                if (isset($value->properties->arrival->runwayTime->estimated)) {
//                    $flight->arrival_runway_time_estimated = $value->properties->arrival->runwayTime->estimated;
//                }
//                if (isset($value->properties->departure->aerodrome->scheduled)) {
//                    $flight->departure_airport_scheduled = $value->properties->departure->aerodrome->scheduled;
//                }
//                if (isset($value->properties->departure->aerodrome->actual)) {
//                    $flight->departure_airport_actual = $value->properties->departure->aerodrome->actual;
//                }
//                if (isset($value->properties->departure->runwayTime->initial)) {
//                    $flight->departure_runway_time_initial = $value->properties->departure->runwayTime->initial;
//                }
//                if (isset($value->properties->departure->runwayTime->estimated)) {
//                    $flight->departure_runway_time_estimated = $value->properties->departure->runwayTime->estimated;
//                }
//                if (isset($value->properties->flightStatus)) {
//                    $flight->flight_status = $value->properties->flightStatus;
//                }
//                if (isset($value->properties->iataFlightNumber)) {
//                    $flight->iata_flight_number = $value->properties->iataFlightNumber;
//                }
//                if (isset($value->properties->timestampProcessed)) {
//                    $flight->timestamp_processed = $value->properties->timestampProcessed;
//                }
//                if (isset($value->properties->aircraftDescription->aircraftCode)) {
//                    $flight->aircraft_code = $value->properties->aircraftDescription->aircraftCode;
//                }
//
//                $flight->save();
//            }
//        }
//    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        //        $xml = XmlParser::extract(Storage::disk('public')->get("/reports/".$new_fileName.""));
//        $response = Unirest\Request::get("https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/pricing/uk2/v1.0/{sessionkey}?pageIndex=0&pageSize=10",
//            array(
//                "X-RapidAPI-Key" => "8f65541bd4msh250fb44b82f4382p1d499fjsn956b443fb6ce"
//            )
//        );

        $titles = DB::table('airports')->pluck('ICAO');

        foreach ($titles as $title) {

            $url = "https://api.laminardata.aero/v1/aerodromes/$title/departures?user_key=5a183c1f789682da267a20a54ca91197";


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
//            dd($json);
//            if (isset($json->features)) {dd($json->features);}

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
                                $flight->arrival_runway_time_initial_date = $array[0] ;
                                $flight->arrival_runway_time_initial_time = $array[1] ;
                            }
                            if (isset($value->properties->arrival->runwayTime->estimated)) {
                                $array = explode("T", $value->properties->arrival->runwayTime->estimated);
                                $flight->arrival_runway_time_estimated_date = $array[0] ;
                                $flight->arrival_runway_time_estimated_time = $array[1] ;
                            }

                            if (isset($value->properties->callsign)) {
                                $flight->callsign = $value->properties->callsign;
                            }

                            if (isset($value->properties->departure->gateTime->estimated)) {
                                $array = explode("T", $value->properties->departure->gateTime->estimated);
                                $flight->gate_time_date = $array[0] ;
                                $flight->gate_time_time = $array[1] ;
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
                                $flight->departure_runway_time_initial_date = $array[0] ;
                                $flight->departure_runway_time_initial_time = $array[1] ;
                            }
                            if (isset($value->properties->departure->runwayTime->estimated)) {
                                $array = explode("T", $value->properties->departure->runwayTime->estimated);
                                $flight->departure_runway_time_estimated_date = $array[0] ;
                                $flight->departure_runway_time_estimated_time = $array[1] ;
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
                                $flight->timestamp_processed_date = $array[0] ;
                                $flight->timestamp_processed_time = $array[1] ;
                            }

                            if (isset($value->properties->aircraftDescription->aircraftCode)) {
                                $flight->aircraft_code = $value->properties->aircraftDescription->aircraftCode;
                            }
                            if (isset($value->properties->aircraftDescription->aircraftRegistration)) {
                                $flight->aircraft_registration = $value->properties->aircraftDescription->aircraftRegistration;
                            }
//                            dd($flight);
                            $flight->save();

                        }
                    }
                }
            }
        }
//        dd(count($json->features));
//        dd($json->features[0]->id);
//        dd($json->features[0]->type);
//        dd($json->features[0]->geometry);
//        dd($json->features[0]->properties->airline);
//        dd($json->features[0]->properties->arrival->aerodrome->scheduled);
//        dd($json->features[0]->properties->arrival->runwayTime->initial);
//        dd($json->features[0]->properties->arrival->runwayTime->estimated);
//        dd($json->features[0]->properties->departure->aerodrome->scheduled);
//        dd($json->features[0]->properties->departure->runwayTime->initial);
//        dd($json->features[0]->properties->flightStatus);
//        dd($json->features[0]->properties->iataFlightNumber);
//        dd($json->features[0]->properties->timestampProcessed);
//        dd($json->features[0]->properties->aircraftDescription->aircraftCode);


//        dd($json->features[0]->properties->airline);
//        function xmlToArray($xml, $options = array())
//        {
//            $defaults = array(
//                'namespaceSeparator' => ':',//you may want this to be something other than a colon
//                'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
//                'alwaysArray' => array(),   //array of xml tag names which should always become arrays
//                'autoArray' => true,        //only create arrays for tags which appear more than once
//                'textContent' => '$',       //key used for the text content of elements
//                'autoText' => true,         //skip textContent key if node has no attributes or child nodes
//                'keySearch' => false,       //optional search and replace on tag and attribute names
//                'keyReplace' => false       //replace values for above search values (as passed to str_replace())
//            );
//            $options = array_merge($defaults, $options);
//            $namespaces = $xml->getDocNamespaces();
//            $namespaces[''] = null; //add base (empty) namespace
//
//            //get attributes from all namespaces
//            $attributesArray = array();
//            foreach ($namespaces as $prefix => $namespace) {
//                foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
//                    //replace characters in attribute name
//                    if ($options['keySearch']) $attributeName =
//                        str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
//                    $attributeKey = $options['attributePrefix']
//                        . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
//                        . $attributeName;
//                    $attributesArray[$attributeKey] = (string)$attribute;
//                }
//            }
//
//            //get child nodes from all namespaces
//            $tagsArray = array();
//            foreach ($namespaces as $prefix => $namespace) {
//                foreach ($xml->children($namespace) as $childXml) {
//                    //recurse into child nodes
//                    $childArray = xmlToArray($childXml, $options);
//                    list($childTagName, $childProperties) = each($childArray);
//
//                    //replace characters in tag name
//                    if ($options['keySearch']) $childTagName =
//                        str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
//                    //add namespace prefix, if any
//                    if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
//
//                    if (!isset($tagsArray[$childTagName])) {
//                        //only entry with this key
//                        //test if tags of this type should always be arrays, no matter the element count
//                        $tagsArray[$childTagName] =
//                            in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
//                                ? array($childProperties) : $childProperties;
//                    } elseif (
//                        is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
//                        === range(0, count($tagsArray[$childTagName]) - 1)
//                    ) {
//                        //key already exists and is integer indexed array
//                        $tagsArray[$childTagName][] = $childProperties;
//                    } else {
//                        //key exists so convert to integer indexed array with previous value in position 0
//                        $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
//                    }
//                }
//            }
//
//            //get text content of node
//            $textContentArray = array();
//            $plainText = trim((string)$xml);
//            if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
//
//            //stick it all together
//            $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
//                ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
//
//            //return node as array
//            return array(
//                $xml->getName() => $propertiesArray
//            );
//        }


//
//        $response = $response->raw_body;
//
//        dd($response->body);
//        Storage::put('file1.xml', $response);
//
//        $contents = Storage::get('file1.xml');


//        $xmlNode = Storage::get('file1.xml');
//        $arrayData = xmlToArray($xmlNode);

//        $xml = XmlParser::load(Storage::get('file1.xml'));
//        $userdata = $xml->parse([
//            'id' => ['Flight' => 'Flight.aircraftDescription'],
//        ]);


//        $xmlNode = simplexml_import_dom(file_get_contents($url));
//        $arrayData = xmlToArray($xmlNode);

//        dd(xml_parse($response));
//        dd(json_encode($response->raw_body));

//        $xml = XmlParser::load($response);
//        $user = $xml->parse([
//            'flight' => ['Flight' => 'arrival']
//        ]);

//        $xml = file_get_contents('https://api.laminardata.aero/v1/aerodromes/EDDB/departures?user_key=5a183c1f789682da267a20a54ca91197&status=scheduled');

//        dd($response);

//        $response = $response->raw_body;

//        Storage::put('file1.xml', $response);
//
//        $contents = Storage::get('file1.xml');
//
//        dd($contents);

//        dd($xml->getContent());

//                dd($response);
//        foreach ($response as $item) {
//        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function show(Flight $flight)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function edit(Flight $flight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flight $flight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flight $flight)
    {
        //
    }
}
