<?php

//use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');

Route::get('hotels&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@HRSHotels');

Route::get('roomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSRoomsPrices');

Route::get('competitorsavgprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsAvgPrices');

Route::get('competitorsroomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsPrices');

Route::get('competitorsroomsavgprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsAvgPrices');

Route::get('events&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@Events');


Route::get('competitorspricesapex&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={datefrom}&dateto={dateto}&competitorsid={competitorsid}&room={room}', 'Api\APIController@HRSHotelsCompetitorsPricesApex');
