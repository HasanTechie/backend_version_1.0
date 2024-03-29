<?php

namespace App\Http\Controllers;

use DB;
use App\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
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
    }

    public function hotel1Prices()
    {
        $prices = DB::table('z_ignore_rooms_prices_chain_baglioni')
            ->paginate(20);

        return view('roomsprices.hotel1.index', compact('prices'));
    }

    public function hotel1Show($id)
    {

        $prices = DB::table('z_ignore_rooms_prices_chain_baglioni')->where('uid', '=', $id)->get();

        dd(unserialize($prices[0]->room_all_rates_and_details));
    }

    public function hotel2Prices()
    {
        $prices = DB::table('z_ignore_rooms_prices_hotel_aquaeductus')->inRandomOrder()
            ->paginate(20);

        return view('roomsprices.hotel2.index', compact('prices'));
    }

    public function hotel2Show($id)
    {

        $prices = DB::table('z_ignore_rooms_prices_hotel_aquaeductus')->where('uid', '=', $id)->get();

        dd($prices[0]);
    }

    public function hotel3Prices()
    {
        $prices = DB::table('z_ignore_rooms_prices_hotel_novecento')->inRandomOrder()
            ->paginate(20);

        return view('roomsprices.hotel3.index', compact('prices'));
    }

    public function hotel3Show($id)
    {

        $prices = DB::table('z_ignore_rooms_prices_hotel_novecento')->where('uid', '=', $id)->get();

        dd($prices[0]);
    }

    public function verticalbookingPrices()
    {
        $prices = DB::table('z_ignore_rooms_prices_vertical_booking')
            ->paginate(20);

        return view('roomsprices.verticalbooking.index', compact('prices'));
    }

    public function verticalbookingShow($id)
    {

        $prices = DB::table('z_ignore_rooms_prices_vertical_booking')->where('uid', '=', $id)->get();

        dd($prices[0]);
    }

    public function EurobookingsRoomsPrices($id)
    {

        $prices = DB::table('rooms_prices_eurobookings')->where('hotel_uid', '=', $id)->paginate(20);

        return view('roomsprices.eurobookings.index', compact('prices'));
    }

    public function EurobookingsRoomsPricesShow($uid, $id)
    {
        $prices = DB::table('rooms_prices_eurobookings')->where('uid', '=', $id)->get();
        dd($prices[0]);
    }


    public function HRSRoomsPrices($id)
    {
        $rooms = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->where('hotel_uid', '=', $id)->paginate(20);
        return view('roomsprices.hrs.index', compact('rooms'));
    }

    public function HRSRoomsPricesShow($uid, $id)
    {
        $prices = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->where('rooms_hrs.uid', '=', $id)->get();
        dd($prices[0]);
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
     * @param \App\Price $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Price $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Price $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Price $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }
}
