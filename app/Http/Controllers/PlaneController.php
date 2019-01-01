<?php

namespace App\Http\Controllers;

use App\Plane;
use Illuminate\Http\Request;

class PlaneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $url = "https://raw.githubusercontent.com/jpatokal/openflights/master/data/planes.dat";
//
//        $contenta = file_get_contents($url);
//
//        $contentb = explode("\n", $contenta);
//
//        foreach ($contentb as $line){
//            $content = explode(",",$line);
//        }

//        foreach ($content as $line) {
//            $plane = new Plane();
//
//            $plane->name = $line;
//            $plane->IATA = 'testing';
//            $plane->ICAO = 'testing';
//
//            $plane->save();
//        }

//        return $content;

        $airports = Airport::take(2000)->get();; //limit to 2000
        return view('planes.index', compact('planes'));
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
     * @param  \App\Plane $plane
     * @return \Illuminate\Http\Response
     */
    public function show(Plane $plane)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plane $plane
     * @return \Illuminate\Http\Response
     */
    public function edit(Plane $plane)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Plane $plane
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plane $plane)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plane $plane
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plane $plane)
    {
        //
    }
}
