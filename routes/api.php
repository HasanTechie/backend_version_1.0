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

Route::get('roomsprices&get={get}&hoteluid={hotel_uid}&datefrom={dateFrom}&dateto={dateTo}&apiKey={apiKey}', 'ApiController@HRSRoomsPrices');

Route::get('competitors&hoteluid={hotel_uid}&datefrom={dateFrom}&dateto={dateTo}&apiKey={apiKey}', 'HotelController@showCompetitor');
