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
        $url = "https://raw.githubusercontent.com/jpatokal/openflights/master/data/routes.dat";

        $contenta = file_get_contents($url);

        $content = explode("\n",$contenta);

        set_time_limit(180);

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
        return view('routes.index',compact('content'));
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
