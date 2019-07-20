<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');


Route::post('uploadImages', 'Api\FilesController@imagesStore')->middleware('auth:api');
Route::post('uploadCSVs', 'Api\FilesController@CSVsStore')->middleware('auth:api');


Route::get('competitors', 'Api\CompetitorsAPIController@index')->middleware('auth:api');
Route::post('competitors', 'Api\CompetitorsAPIController@store')->middleware('auth:api');
Route::get('competitors&user_id={user_id}&hotel_id={hotel_id}', 'Api\CompetitorsAPIController@destroy')->middleware('auth:api');

Route::get('hotels&get={get}&apiKey={apiKey}&city={city}', 'Api\HotelsAPIController@HRSHotels');


Route::get('roomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}', 'Api\HotelsAPIController@HRSRoomsPrices');

Route::get('allroomsprices&get={get}&apiKey={apiKey}&userid={user_id}&datefrom={dateFrom}&dateto={dateTo}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorAllRoomsPrices');

Route::get('events&get={get}&apiKey={apiKey}&city={city}', 'Api\EventsAPIController@Events');

Route::get('competitorspricesapex&get={get}&apiKey={apiKey}&userid={userid}&datefrom={datefrom}&dateto={dateto}&room={room}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorsPricesApex');

//new method
Route::get('competitorsavgprices&get={get}&apiKey={apiKey}&userid={userid}&datefrom={dateFrom}&dateto={dateTo}&room={room}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorsAvgPrices');
//new method
Route::get('competitorsroomsprices&get={get}&apiKey={apiKey}&userid={userid}&datefrom={dateFrom}&dateto={dateTo}&room={room}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorsRoomsPrices');
//new method
Route::get('competitorsroomsavgprices&get={get}&apiKey={apiKey}&userid={userid}&datefrom={dateFrom}&dateto={dateTo}&room={room}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorsRoomsAvgPrices');

//old method
Route::get('competitorsavgpricesold&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorsAvgPricesOld');
//old method
Route::get('competitorsroomspricesold&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorsRoomsPricesOld');
//old method
Route::get('competitorsroomsavgpricesold&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\CompetitorsPriceAPIController@HRSHotelsCompetitorsRoomsAvgPricesOld');
