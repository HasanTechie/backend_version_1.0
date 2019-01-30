<?php

namespace App\Http\Controllers;

use Google\GTrends;

use App\Trend;
use Illuminate\Http\Request;

class TrendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        # This options are by default if none provided
        $options = [
            'hl'  => 'en-US',
            'tz'  => -60, # last hour
            'geo' => 'IE',
        ];
        $gt = new GTrends($options);
        dd($gt->interestOverTime('Dublin'));
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
     * @param  \App\Trend  $trend
     * @return \Illuminate\Http\Response
     */
    public function show(Trend $trend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trend  $trend
     * @return \Illuminate\Http\Response
     */
    public function edit(Trend $trend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Trend  $trend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trend $trend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trend  $trend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trend $trend)
    {
        //
    }
}
