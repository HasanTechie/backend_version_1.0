<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::get('competitors', 'Api\CompetitorsAPIController@index')->middleware('auth:api');
Route::post('competitors', 'Api\CompetitorsAPIController@store')->middleware('auth:api');
Route::get('competitors&user_id={user_id}&hotel_id={hotel_id}', 'Api\CompetitorsAPIController@destroy')->middleware('auth:api');

Route::get('hotels&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@HRSHotels');

Route::get('roomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSRoomsPrices');
//old
Route::get('competitorsavgpricesold&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsAvgPricesOld');
//old
Route::get('competitorsroomspricesold&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsPricesOld');
//old
Route::get('competitorsroomsavgpricesold&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsAvgPricesOld');

//new
Route::get('competitorsavgprices&get={get}&apiKey={apiKey}&userid={userid}&datefrom={dateFrom}&dateto={dateTo}&room={room}', 'Api\APIController@HRSHotelsCompetitorsAvgPrices');
//new
Route::get('competitorsroomsprices&get={get}&apiKey={apiKey}&userid={userid}&&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSHotelsCompetitorsRoomsPrices');
//new
Route::get('competitorsroomsavgprices&get={get}&apiKey={apiKey}&userid={userid}&&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSHotelsCompetitorsRoomsAvgPrices');

Route::get('events&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@Events');

Route::get('competitorspricesapex&get={get}&apiKey={apiKey}&userid={userid}&datefrom={datefrom}&dateto={dateto}&room={room}', 'Api\APIController@HRSHotelsCompetitorsPricesApex');
