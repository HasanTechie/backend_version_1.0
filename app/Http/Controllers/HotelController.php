<?php

namespace App\Http\Controllers;

use App\Hotel;
use Illuminate\Http\Request;

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

        $googlePlaces = new PlacesApi($key);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=hotel in Berlin, Germany&inputtype=textquery&fields=photos,formatted_address,name,rating,opening_hours,geometry&key=$key");
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


        $response = json_decode($response->getBody());

        dd($response->result);
//        return view('hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
