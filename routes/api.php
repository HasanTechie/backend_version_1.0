<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');

Route::apiResource('/competitors', 'Api\CompetitorsAPIController')->middleware('auth:api');

Route::get('hotels&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@HRSHotels');

Route::get('roomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}', 'Api\APIController@HRSRoomsPrices');

Route::get('competitorsavgprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsAvgPrices');

Route::get('competitorsroomsprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsPrices');

Route::get('competitorsroomsavgprices&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={dateFrom}&dateto={dateTo}&competitorsid={competitorsid}', 'Api\APIController@HRSHotelsCompetitorsRoomsAvgPrices');

Route::get('events&get={get}&apiKey={apiKey}&city={city}', 'Api\APIController@Events');

Route::get('competitorspricesapex&get={get}&apiKey={apiKey}&hotelid={hotel_id}&datefrom={datefrom}&dateto={dateto}&competitorsid={competitorsid}&room={room}', 'Api\APIController@HRSHotelsCompetitorsPricesApex');
