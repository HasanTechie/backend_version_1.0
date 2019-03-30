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

//
//Route::get('/hotels/create', 'HotelController@create');
//Route::get('/hotels', 'HotelController@index');
//Route::get('/hotels/hotels/{hotel}', 'HotelController@show');
//Route::get('/hotels/test1', 'HotelController@test1');
//Route::get('/hotels/test2', 'HotelController@test2');
//Route::get('/hotels/test3', 'HotelController@test3');
//Route::get('/hotels/getPlaces', 'HotelController@getPlaces');
//Route::get('/hotels/getPlaceDetails', 'HotelController@getPlaceDetails');
//Route::get('/hotels/getReviewDetails', 'HotelController@getReviewDetails');
//Route::get('/hotels/search', 'HotelController@search');
//Route::post('/hotels/search', 'HotelController@search');

Route::get('/events', 'EventController@index');
Route::get('/events/{event}', 'EventController@show');

Route::get('/trends', 'TrendController@index');
Route::get('/trends/create', 'TrendController@create');
Route::get('/trends/{trend}', 'TrendController@show');
Route::get('/trends/interestovertime/{trend}', 'TrendController@interestovertime');
Route::get('/trends/interestbysubregion/{trend}', 'TrendController@interestbysubregion');
Route::get('/trends/relatedtopics/{trend}', 'TrendController@relatedtopics');
Route::get('/trends/relatedqueries/{trend}', 'TrendController@relatedqueries');


Route::get('/weathers', 'WeatherController@index');
Route::get('/weathers/{weather}', 'WeatherController@show');

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


Route::get('/roomsprices/hotel1/', 'PriceController@hotel1Prices');
Route::get('/roomsprices/hotel1/{id}', 'PriceController@hotel1Show');
Route::get('/roomsprices/hotel2/', 'PriceController@hotel2Prices');
Route::get('/roomsprices/hotel2/{id}', 'PriceController@hotel2Show');
Route::get('/roomsprices/hotel3/', 'PriceController@hotel3Prices');
Route::get('/roomsprices/hotel3/{id}', 'PriceController@hotel3Show');
Route::get('/roomsprices/verticalbooking/', 'PriceController@verticalbookingPrices');
Route::get('/roomsprices/verticalbooking/{id}', 'PriceController@verticalbookingShow');


Route::get('/hotels/eurobookings/roomsprices/{id}', 'PriceController@EurobookingsRoomsPrices');
Route::get('/roomsprices/eurobookings/{uid}/{id}', 'PriceController@EurobookingsRoomsPricesShow');

Route::get('/hotels/eurobookings', 'HotelController@hotelEurobookingsShowAll');
Route::get('/hotels/eurobookings/reviewsontripadvisor/{id}', 'HotelController@hotelEurobookingsReviewsOnTripadvisor');
Route::get('/hotels/eurobookings/details/{id}', 'HotelController@hotelEurobookingsDetails');
Route::get('/hotels/eurobookings/facilities/{id}', 'HotelController@hotelEurobookingsFacilities');
Route::get('/hotels/eurobookings/hotelinfo/{id}', 'HotelController@hotelEurobookingsHotelInfo');
Route::get('/hotels/eurobookings/{id}', 'HotelController@hotelEurobookingsShowDetails');


Route::get('/hotels/hrs', 'HotelController@hotelHRSShowAll');
Route::get('/hotels/hrs/{id}', 'HotelController@hotelHRSShowDetails');

Route::get('/hotels/hrs/roomsprices/{id}', 'PriceController@HRSRoomsPrices');
Route::get('/roomsprices/hrs/{uid}/{id}', 'PriceController@HRSRoomsPricesShow');


Route::get('/airports', 'AirportController@index');
Route::get('/airlines', 'AirlineController@index');
Route::get('/routes', 'RouteController@index');
Route::get('/routes/create', 'RouteController@create');
Route::get('/planes', 'PlaneController@index');

Auth::routes();

Route::prefix('admin')->group(function () {

    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');

    // Password reset routes
    Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset')->name('admin.password.update');
    Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');

});

Route::get('/home', 'HomeController@index');

Route::get('/frontend', function (){
    return view('frontend');
});

