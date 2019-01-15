<?php

namespace App\Http\Controllers;

use App\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $url = "https://raw.githubusercontent.com/jpatokal/openflights/master/data/routes.dat";
//
//        $contenta = file_get_contents($url);
//
//        $content = explode("\n",$contenta);

//        set_time_limit(180);

//        foreach ($content as $line)
//        {
//            $route = new Route();
//
//            $route->airline = $line;
//            $route->airline_id = 'testing';
//            $route->source_airport = 'testing';
//            $route->source_airport_id = 'testing';
//            $route->destination_airport_id = 'testing';
//            $route->codeshare = 'testing';
//            $route->stops = 1;
//            $route->equipment = 2;
//
//            $route->save();
//
//        }
//

//        return $content;

        $routes = Route::take(2000)->get();; //limit to 2000
        return view('routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // Unbeatabil => AIzaSyBA1e2qFRgt6xW17Goo_IwPAWkCcrqXTqY
        // soliDPS => AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg
         $key = 'AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg';


        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/directions/json?origin=place_id:ChIJ685WIFYViEgRHlHvBbiD5nE&destination=place_id:ChIJA01I-8YVhkgRGJb0fW4UX7Y&key=$key"); //free but only one result

        $data= json_decode($response->getBody());

        return view('routes.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        //
    }
}
