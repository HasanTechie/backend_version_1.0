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
//        $hotels = Hotel::all();

        $key = 'AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg';
        session_start();

//        $_SESSION['next_page_token']='';


//        $googlePlaces = new PlacesApi($key);
        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=Hotel%20in%20Berlin&inputtype=textquery&fields=photos,formatted_address,name,rating,opening_hours,geometry&key=$key"); //free but only one result
//        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ42MzYk1QqEcRhlX0r_-WtI8&fields=name,rating,price_level,formatted_phone_number&key=$key");
        if (empty($_SESSION['next_page_token'])) {
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20near%20Berlin,%20Germany&key=$key&pagetoken=" . $_SESSION['next_page_token'] . "");
        } else {
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20near%20Berlin,%20Germany&key=$key");
//            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJN1t_tDeuEmsRUsoyG83frY4&fields=name,rating,formatted_phone_number,address_component, adr_address, alt_id, formatted_address,geometry,icon,id,name,permanently_closed,photo,place_id,plus_code,scope,type,url,utc_offset,price_level,rating,review,formatted_phone_number,international_phone_number,opening_hours,website,vicinity&key=$key");
        }
//        $response->getStatusCode();
//// 200
//        $response->getHeaderLine('content-type');
//// 'application/json; charset=utf8'
//        $response->getBody();
// '{"id": 1420053, "name": "guzzle", ...}'

//        $response = $googlePlaces->placeAutocomplete('hotel in berlin');
//        $response= "";
//        $request = new \GuzzleHttp\Psr7\Request('GET', 'https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ42MzYk1QqEcRhlX0r_-WtI8&fields=name,rating,formatted_phone_number&key=AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg');
//        $promise = $client->sendAsync($request)->then(function ($response) {
//            echo 'I completed! ' . $response->getBody();
//        });
//        $promise->wait();

        $res = json_decode($response->getBody());
//
        if (!empty($res->next_page_token)) {
            $_SESSION['next_page_token'] = $res->next_page_token;
        }

        foreach ($res->results as $hotelinstance) {

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
                $hotel->all_data = serialize($hotelinstance);
                $hotel->save();
            }
        }

        dd($res);
//        return view('hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $results = DB::select('select * from hotels where hotel_id = :id', ['id' => 'ChIJwUq6mx5OqEcREPAUaRdFmT4']);

        dd(empty($results));
//        //
//        $hotels = Hotels::get(); //limit to 2000
//
////        $res = unserialize($hotel[0]->all_data);
//            $data = "";
//        foreach ($hotels as $instance){
////            $hotel = new Hotel();
////            $hotel->address= $instance->formatted_address;
////
//            dd(unserialize($instance->all_data));
//        }
//        dd($res);
//        dd($data);
//        return view('flights.index', compact('flights'));
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
