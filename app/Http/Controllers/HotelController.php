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
        //$key = 'AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg';

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
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=Hotel%20in%20Berlin&inputtype=textquery&fields=photos,formatted_address,name,rating,opening_hours,geometry&key=$key"); //free but only one result
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ42MzYk1QqEcRhlX0r_-WtI8&fields=name,rating,price_level,formatted_phone_number&key=$key");
        if (!empty($_SESSION['next_page_token'])) {
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key&pagetoken=" . $_SESSION['next_page_token'] . "");
        } else {
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key");
        }
        */

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
