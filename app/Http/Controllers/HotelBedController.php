<?php

namespace App\Http\Controllers;

use App\HotelBed;
use Illuminate\Http\Request;

use DB;

class HotelBedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hotelbeds = HotelBed::orderBy('id', 'ASC')->paginate(25);;
        return view('hotelbeds.index', compact('hotelbeds'));
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
     * @param  \App\HotelBed  $hotelBed
     * @return \Illuminate\Http\Response
     */
    public function show($HotelBed)
    {
        //
        $hotelbed = DB::table('hotel_beds')->where('id', '=', $HotelBed)->get();

        return view('hotelbeds.show', compact('hotelbed'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HotelBed  $hotelBed
     * @return \Illuminate\Http\Response
     */
    public function edit(HotelBed $hotelBed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HotelBed  $hotelBed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotelBed $hotelBed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HotelBed  $hotelBed
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelBed $hotelBed)
    {
        //
    }
}
