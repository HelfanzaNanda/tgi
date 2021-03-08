<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
	Route::get('/login', 'Auth\LoginController@index');
	Route::post('/login', 'Auth\LoginController@authorizes');
	Route::get('/logout', 'Auth\LoginController@logout');

	Route::get('/city_by_province/{province_id}', ['as' => 'show.city', 'uses' => 'Ref\CityController@cityByProvince']);
	
	Route::group(['middleware' => 'auth.primary'], function () {
		Route::group(['namespace' => 'Dashboard'], function () {
			Route::get('/', 'DashboardController@index');
			Route::get('/home', 'DashboardController@index');
		});
	});
});