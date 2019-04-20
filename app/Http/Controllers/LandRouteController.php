<?php

namespace App\Http\Controllers;

use App\LandRoute;
use Illuminate\Http\Request;

use DB;

class LandRouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //
        // Unbeatabil => AIzaSyBA1e2qFRgt6xW17Goo_IwPAWkCcrqXTqY
        // soliDPS => AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg
        $key = 'AIzaSyDQb12hXetLTc0FEuLPKrs9W1-9MXDaPUU';

//
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/directions/json?origin=place_id:ChIJ685WIFYViEgRHlHvBbiD5nE&destination=place_id:ChIJA01I-8YVhkgRGJb0fW4UX7Y&key=$key"); //free but only one result
//
//        $data= json_decode($response->getBody());
        $data = " ";
//        dd($data);


        $hotels = DB::table('hotels_basic_data')->where('city', '=', 'berlin')->get();

        $attractions = DB::table('places')->where([
            ['category', '=', 'attraction'],
            ['city', '=', 'berlin'],
        ])->get();

        return view('landroutes.index', compact(['hotels', 'attractions', 'key']));
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

    public function test1()
    {
        $key = 'AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/directions/json?origin=place_id:ChIJ685WIFYViEgRHlHvBbiD5nE&destination=place_id:ChIJA01I-8YVhkgRGJb0fW4UX7Y&departure_time=now&key=$key"); //free but only one result

        $data = json_decode($response->getBody());

        dd($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\LandRoute $landRoute
     * @return \Illuminate\Http\Response
     */
    public function show(LandRoute $landRoute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\LandRoute $landRoute
     * @return \Illuminate\Http\Response
     */
    public function edit(LandRoute $landRoute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\LandRoute $landRoute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LandRoute $landRoute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\LandRoute $landRoute
     * @return \Illuminate\Http\Response
     */
    public function destroy(LandRoute $landRoute)
    {
        //
    }
}
