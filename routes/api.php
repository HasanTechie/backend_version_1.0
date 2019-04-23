<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('hotels&get={get}&apiKey={apiKey}', 'ApiController@HRSHotels');

Route::get('roomsprices&get={get}&apiKey={apiKey}&hoteluid={hotel_uid}&datefrom={dateFrom}&dateto={dateTo}', 'ApiController@HRSRoomsPrices');

Route::get('competitorsprices&get={get}&apiKey={apiKey}&hoteluid={hotel_uid}&datefrom={dateFrom}&dateto={dateTo}&competitorsuid={competitorsuid}', 'ApiController@HRSHotelsCompetitorsPrices');
