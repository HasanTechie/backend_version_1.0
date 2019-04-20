<?php

namespace App\Http\Controllers;

use App\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
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
//        $url = "https://raw.githubusercontent.com/jpatokal/openflights/master/data/airlines.dat";
//
//        $contenta = file_get_contents($url);
//
//        $content = explode("\n",$contenta);

//        foreach ($content as $line)
//        {
//            $airline = new Airline();
//
//            $airline->airline_id = $line;
//            $airline->name = 'testing';
//            $airline->alias = 'testing';
//            $airline->IATA = 'testing';
//            $airline->ICAO = 'testing';
//            $airline->ICAO = 'testing';
//            $airline->callsign = 1;
//            $airline->country = 2;
//            $airline->active = 3;
//            $airline->save();
//
//
//        }

//        return $content;

        $airlines = Airline::inRandomOrder()->paginate(25); //limit to 2000
        return view('airlines.index', compact('airlines'));
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
     * @param \App\Airline $airline
     * @return \Illuminate\Http\Response
     */
    public function show(Airline $airline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Airline $airline
     * @return \Illuminate\Http\Response
     */
    public function edit(Airline $airline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Airline $airline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airline $airline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Airline $airline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airline $airline)
    {
        //
    }
}
