<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::get('competitors', 'Api\CompetitorsAPIController@index')->middleware('auth:api');
Route::post('competitors', 'Api\CompetitorsAPIController@store')->middleware('auth:api');
Route::get('competitors&user_id={user_id}&hotel_id={hotel_id}', 'Api\CompetitorsAPIController@destroy')->middleware('auth:api');

Route::get('hotels&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@HRSHotels');

Route::get('roomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSRoomsPrices');

Route::get('competitorsavgprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsAvgPrices');
//old
Route::get('competitorsroomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsPrices');
//old
Route::get('competitorsroomsavgprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsAvgPrices');

//new
Route::get('competitorsroomsprices&get={get}&apiKey={apiKey}&userid={userid}&&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSHotelsCompetitorsRoomsPricesNew');
//new
Route::get('competitorsroomsavgprices&get={get}&apiKey={apiKey}&userid={userid}&&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSHotelsCompetitorsRoomsAvgPricesNew');

Route::get('events&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@Events');

Route::get('competitorspricesapex&get={get}&apiKey={apiKey}&userid={userid}&datefrom={datefrom}&dateto={dateto}&room={room}', 'Api\APIController@HRSHotelsCompetitorsPricesApex');
