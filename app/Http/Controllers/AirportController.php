<?php

namespace App\Http\Controllers;

use App\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $url = "https://raw.githubusercontent.com/jpatokal/openflights/master/data/airports.dat";
//
//        $contenta = file_get_contents($url);
//
//        $content = explode("\n",$contenta);

//        foreach ($content as $line)
//        {
//            $airport = new Airport();
//
//            $airport->airport_id = $line;
//            $airport->name = 'testing';
//            $airport->city = 'testing';
//            $airport->country = 'testing';
//            $airport->IATA = 'testing';
//            $airport->ICAO = 'testing';
//            $airport->latitude = 1;
//            $airport->longitude = 2;
//            $airport->altitude = 3;
//            $airport->timezone = 4;
//            $airport->DST = 'testing';
//            $airport->Tz_database_time_zone = 'testing';
//            $airport->type = 'testing';
//            $airport->source = 'testing';
//            $airport->save();
//
//        }

//        return $content;

        $airports = Airport::take(2000)->get();; //limit to 2000
        return view('airports.index', compact('airports'));
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
     * @param  \App\Airport $airport
     * @return \Illuminate\Http\Response
     */
    public function show(Airport $airport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Airport $airport
     * @return \Illuminate\Http\Response
     */
    public function edit(Airport $airport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Airport $airport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airport $airport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Airport $airport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airport $airport)
    {
        //
    }
}
