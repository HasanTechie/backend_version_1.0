<?php

namespace App\Http\Controllers;

use App\HotelBasicData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelBasicDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hotels = HotelBasicData::paginate(25);
        return view('hotels.hotelsbasicdata.index', compact('hotels'));
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
     * @param \App\HotelBasicData $hotelBasicData
     * @return \Illuminate\Http\Response
     */
    public function show($hotelBasicData)
    {
        //
        $hotel = HotelBasicData::where('uid', $hotelBasicData)->get();
        return view('hotels.hotelsbasicdata.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\HotelBasicData $hotelBasicData
     * @return \Illuminate\Http\Response
     */
    public function edit(HotelBasicData $hotelBasicData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\HotelBasicData $hotelBasicData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotelBasicData $hotelBasicData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\HotelBasicData $hotelBasicData
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelBasicData $hotelBasicData)
    {
        //
    }
}
