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
});

Route::get('/hotels', 'HotelController@index');
Route::get('/hotels/create', 'HotelController@create');
Route::get('/hotels/test1', 'HotelController@test1');
Route::get('/hotels/test2', 'HotelController@test2');

Route::get('/flights', 'FlightController@index');
Route::get('/flights/create', 'FlightController@create');
Route::get('/flights/current', 'FlightController@current');
Route::get('/flights/search', 'FlightController@search');
Route::post('/flights/search', 'FlightController@search');
Route::get('/flights/test', 'FlightController@test');

Route::get('/airports', 'AirportController@index');
Route::get('/airlines', 'AirlineController@index');
Route::get('/routes', 'RouteController@index');
Route::get('/planes', 'PlaneController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
