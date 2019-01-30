<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/places', 'PlaceController@index');
Route::get('/places/{place}', 'PlaceController@show');


Route::get('/hotels/create', 'HotelController@create');
Route::get('/hotels', 'HotelController@index');
Route::get('/hotels/{hotel}', 'HotelController@show');
Route::get('/hotels/test1', 'HotelController@test1');
Route::get('/hotels/test2', 'HotelController@test2');
Route::get('/hotels/test3', 'HotelController@test3');
Route::get('/hotels/getPlaces', 'HotelController@getPlaces');
Route::get('/hotels/getPlaceDetails', 'HotelController@getPlaceDetails');
Route::get('/hotels/getReviewDetails', 'HotelController@getReviewDetails');
Route::get('/hotels/search', 'HotelController@search');
Route::post('/hotels/search', 'HotelController@search');

Route::get('/events', 'EventController@index');
Route::get('/events/{event}', 'EventController@show');

Route::get('/trends', 'TrendController@index');
Route::get('/trends/{trend}', 'TrendController@show');

Route::get('/landroutes/', 'LandRouteController@index');
Route::get('/landroutes/test1', 'LandRouteController@test1');

Route::get('/flights/search', 'FlightController@search');
Route::get('/flights', 'FlightController@index');
Route::get('/flights/{flight}', 'FlightController@show');
Route::get('/flights/create', 'FlightController@create');
Route::get('/flights/current', 'FlightController@current');
Route::post('/flights/search', 'FlightController@search');
Route::get('/flights/test', 'FlightController@test');

Route::get('/flights/test1/{id}', 'FlightController@test1');

Route::get('/flightsprices', 'FlightController@flightPrices');
Route::get('/flightsprices/{flight}', 'FlightController@flightPricesShow');
Route::get('/flightsprices/totalflights/{flight}', 'FlightController@flightsShow');

Route::get('/airports', 'AirportController@index');
Route::get('/airlines', 'AirlineController@index');
Route::get('/routes', 'RouteController@index');
Route::get('/routes/create', 'RouteController@create');
Route::get('/planes', 'PlaneController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
