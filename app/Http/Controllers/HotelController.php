<?php

namespace App\Http\Controllers;

use App\Hotel;
use Illuminate\Http\Request;

use DB;

use SKAgarwal\GoogleApi\PlacesApi;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hotels = Hotel::orderBy('total_ratings', 'desc')->get();
        return view('hotels.index', compact('hotels'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $key = 'AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg';

        /*
        session_start();
        $_SESSION['next_page_token']='';
        */

        /*
        $googlePlaces = new PlacesApi($key);
        $response = $googlePlaces->placeAutocomplete('hotels in berlin');
        */

        /*
        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=Lindner%20Hotel%20Am%20Ku'damm&inputtype=textquery&key=$key"); //free but only one result
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJoWWNpP5QqEcRiCJoLCXTM2o&key=$key");
//        if (!empty($_SESSION['next_page_token'])) {
//            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key&pagetoken=" . $_SESSION['next_page_token'] . "");
//        } else {
//            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key");
//        }

        dd(json_decode($response->getBody()));
        /*
        $hotels = DB::table('hotels')->select('hotel_id')->get();

        foreach ($hotels as $hotel) {
            $hotel_id = $hotel->hotel_id;
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=$hotel_id&key=$key");

            $response = json_decode($response->getBody());

            $response = $response->result;

            $address_components = [];

            foreach ($response->address_components as $componentInstance) {
                foreach ($componentInstance->types as $type)

                    if (!is_array($type)) {
                        if ($type == 'country') {
                            $address_components['country'] = $componentInstance->long_name;
                        }
                        if ($type == 'locality') {
                            $address_components['locality'] = $componentInstance->long_name;
                        }
                        if ($type == 'sublocality') {
                            $address_components['sublocality'] = $componentInstance->long_name;
                        }
                        if ($type == 'route') {
                            $address_components['route'] = $componentInstance->long_name;
                        }
                        if ($type == 'street_number') {
                            $address_components['street_number'] = $componentInstance->long_name;
                        }
                        if ($type == 'postal_code') {
                            $address_components['postal_code'] = $componentInstance->long_name;
                        }
                    }
            }

            DB::table('hotels')
                ->where('hotel_id', $hotel_id)
                ->update([
                    'website' => $response->website,
                    'phone' => $response->formatted_phone_number,
                    'international_phone' => $response->international_phone_number,
                    'country' => $address_components['country'],
                    'locality' => $address_components['locality'],
                    'sublocality' => $address_components['sublocality'],
                    'route' => $address_components['route'],
                    'street_number' => $address_components['street_number'],
                    'postal_code' => $address_components['postal_code'],
                    'address_components' => serialize($response->address_components),
                    'opening_hours' => serialize($response->opening_hours),
                    'photos' => serialize($response->photos),
                    'reviews' => serialize($response->reviews),
                    'adr_address' => $response->adr_address,
                    'maps_url' => $response->url,
                    'vicinity' => $response->vicinity,
                    'total_ratings' => $response->user_ratings_total,
                    'rating' => $response->rating,
                    'name' => $response->name,
                    'unk_id' => $response->id,
                    'address' => $response->formatted_address,
                    'geometry' => serialize($response->geometry),
                    'plus_code' => serialize($response->plus_code),
                    'rating' => $response->rating,
                    'all_data' => serialize($response),

                ]);
        }
        */


        /*
        //asynchronous
        $request = new \GuzzleHttp\Psr7\Request('GET', 'https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ42MzYk1QqEcRhlX0r_-WtI8&fields=name,rating,formatted_phone_number&key=AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg');
        $promise = $client->sendAsync($request)->then(function ($response) {
            echo 'I completed! ' . $response->getBody();
        });
        $promise->wait();
        */


        /*
        if (!empty($res->next_page_token)) {
            $_SESSION['next_page_token'] = $res->next_page_token;
        }
        */

        /*
        foreach ($res->result as $hotelinstance) {

            $results = DB::select('select * from hotels where hotel_id = :id', ['id' => "$hotelinstance->place_id"]);

            if (empty($results)) {

                $hotel = new Hotel();

                $hotel->name = $hotelinstance->name;
                $hotel->hotel_id = $hotelinstance->place_id;
                $hotel->unk_id = $hotelinstance->id;
                $hotel->address = $hotelinstance->formatted_address;
                $hotel->geometry = serialize($hotelinstance->geometry);
                $hotel->plus_code = serialize($hotelinstance->plus_code);
                $hotel->rating = $hotelinstance->rating;
                $hotel->total_ratings = $hotelinstance->user_ratings_total;
                $hotel->all_data = serialize($res->result);
                $hotel->save();

                dd($res);
            }
        }
        */

        /* // FOR makcorps API
        $data = [
            'username' => 'sayyid',
            'password' => 'minimum;\'90',
        ];

        $curl1 = curl_init();

        curl_setopt_array($curl1, array(
            CURLOPT_URL => "https://api.makcorps.com/auth",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers */
//                "accept: */*",
        /*                "accept-language: en-US,en;q=0.8",
                        "content-type: application/json",
                    ),
                ));

                $response1 = curl_exec($curl1);
                $err = curl_error($curl1);

                curl_close($curl1);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $token = (json_decode($response1)->access_token);
                }


                $curl = curl_init();

        //        $url="https://api.makcorps.com/king/v2/berlin/2019-01-18/2019-01-20";
                $url="https://api.makcorps.com/free/berlin";

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "$url",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                        'Content-Type: application/json',
                        "Authorization: JWT $token"
                    ),
                ));

        //        dd($token);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    dd(json_decode($response));
                }
                */
        /*
        // for tour-pedia.org all data
            $cities = array("Amsterdam", "Barcelona", "Berlin", "Dubai", "London", "Paris", "Rome", "Tuscany");

            foreach ($cities as $city) {
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', "http://tour-pedia.org/api/getPlaces?location=$city"); //free but only one result

                $response = json_decode($response->getBody());

                foreach ($response as $instance) {
                    //place_id,name,address,category,location,lat,lng,numReviews,reviews,polarity,details,originalId,subCategory,source
                    DB::table('places')->insert([
                        'place_id' => (isset($instance->id) ? $instance->id : null),
                        'name' => (isset($instance->name) ? $instance->name : 'Not Available'),
                        'address' => (isset($instance->address) ? $instance->address : 'Not Available'),
                        'category' => (isset($instance->category) ? $instance->category : 'Not Available'),
                        'location' => (isset($instance->location) ? $instance->location : 'Not Available'),
                        'lat' => (isset($instance->lat) ? $instance->lat : null),
                        'lng' => (isset($instance->lng) ? $instance->lng : null),
                        'numReviews' => (isset($instance->numReviews) ? $instance->numReviews : null),
                        'reviews' => (isset($instance->reviews) ? $instance->reviews : 'Not Available'),
                        'polarity' => (isset($instance->polarity) ? $instance->polarity : null),
                        'details' => (isset($instance->details) ? $instance->details : 'Not Available'),
                        'originalId' => (isset($instance->originalId) ? $instance->originalId : 'Not Available'),
                        'subCategory' => (isset($instance->subCategory) ? $instance->subCategory : 'Not Available'),
                        'source' => 'http://tour-pedia.org',
                        'all_data' => serialize($instance),
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);

                    dd($instance);
                }
            }
        */

//
        $places = DB::table('places')->select('place_id')->get();
        foreach ($places as $place) {
            $place = $place->place_id;
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', "http://tour-pedia.org/api/getPlaceDetails?id=204042"); //free
            $response = json_decode($response->getBody());

//            dd($response->id);
            dd($response);

            //id": 204042
            //  +"name": "Hotel Bologna"
            //  +"address": "Via Giuseppe Mazzini 57, 56125 Pisa"
            //  +"category": "accommodation"
            //  +"location": "Tuscany"
            //  +"lat": 43.714217886107
            //  +"lng": 10.398866683245
            //  +"services": {#260312 ▶}
            //  +"phone_number": "050 502120"
            //  +"international_phone_number": "+39 050 502120"
            //  +"website": "http://www.hotelbologna.pisa.it/"
            //  +"icon": "http://maps.gstatic.com/mapfiles/place_api/icons/bar-71.png"
            //  +"description": {#260299 ▶}
            //  +"external_urls": {#260308 ▶}
            //  +"statistics": {#260313 ▼
            //    +"Foursquare": {#260301 ▶}
            //    +"Facebook": {#260306 ▶}
            //  }
            //  +"numReviews": 9
            //  +"reviews": array:9 [▼
            //    0 => "52a766baae9eef5a506cbcf2"
            //    1 => "52a766baae9eef5a506cbcf3"
            //    2 => "52a766baae9eef5a506cbcf4"
            //    3 => "52a766baae9eef5a506cbcf5"
            //    4 => "533ecd7fae9eef521e65838c"
            //    5 => "533ecd7fae9eef521e65838d"
            //    6 => "533ecd7fae9eef521e65838e"
            //    7 => "533ecd7fae9eef521e65838f"
            //    8 => "533ecd7fae9eef521e658390"
            //  ]
            //  +"polarity": 8
            $detailed_reviews = "";
            DB::table('places')
                ->where('place_id', $response->id)
                ->update([
                    'name' => (isset($response->name) ? $response->name : 'Not Available'),
                    'address' => (isset($response->address) ? $response->address : 'Not Available'),
                    'phone_number' => (isset($response->phone_number) ? $response->phone_number : 'Not Available'),//
                    'international_phone_number' => (isset($instance->international_phone_number) ? $response->international_phone_number : 'Not Available'),//
                    'website' => (isset($response->website) ? $response->website : 'Not Available'),//
                    'category' => (isset($response->category) ? $response->category : 'Not Available'),
                    'location' => (isset($response->location) ? $response->location : 'Not Available'),
                    'lat' => (isset($response->lat) ? $response->lat : null),
                    'lng' => (isset($response->lng) ? $response->lng : null),
                    'numReviews' => (isset($response->numReviews) ? $response->numReviews : null),
                    'reviews' => (isset($response->reviews) ? $response->reviews : 'Not Available'),
                    'polarity' => (isset($response->polarity) ? $response->polarity : null),
                    'details' => (isset($response->details) ? $response->details : 'Not Available'),
                    'originalId' => (isset($response->originalId) ? $response->originalId : 'Not Available'),
                    'subCategory' => (isset($response->subCategory) ? $response->subCategory : 'Not Available'),
                    'source' => 'http://tour-pedia.org',
                    'description' => (isset($response->description) ? serialize($response->description) : 'Not Available'),//
                    'external_urls' => (isset($response->external_urls) ? serialize($response->external_urls) : 'Not Available'),//
                    'statistics' => (isset($response->statistics) ? serialize($response->statistics) : 'Not Available'),//
                    'reviews' => (isset($response->reviews) ? serialize($response->reviews) : 'Not Available'),//
                    'detailed_reviews' => (isset($response->reviews) ? serialize($detailed_reviews) : 'Not Available'),//
                    'all_data_detailed' => serialize($response),//

                ]);

//            dd($response);
        }
    }


    public function test1()
    {
        $results = DB::select('select * from hotels where id = :id', ['id' => '401']);

        dd(unserialize($results[0]->all_data));
        /*
        //
        $hotels = Hotels::get(); //limit to 2000

//        $res = unserialize($hotel[0]->all_data);
            $data = "";
        foreach ($hotels as $instance){
//            $hotel = new Hotel();
//            $hotel->address= $instance->formatted_address;
//
            dd(unserialize($instance->all_data));
        }
        dd($res);
        dd($data);
        return view('flights.index', compact('flights'));
        */
    }

    public function test2()
    {
        $results = DB::select('select * from hotels where id = :id', ['id' => '1']);

        dd(unserialize($results[0]->all_data));

        /*
        $hotels = Hotels::get(); //limit to 2000

//        $res = unserialize($hotel[0]->all_data);
            $data = "";
        foreach ($hotels as $instance){
//            $hotel = new Hotel();
//            $hotel->address= $instance->formatted_address;
//
            dd(unserialize($instance->all_data));
        }
        dd($res);
        dd($data);
        return view('flights.index', compact('flights'));
        */
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
     * @param  \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function show(Hotel $hotel)
    {
        //
        return view('hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hotel $hotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
        //
    }
}
