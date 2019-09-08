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


Route::middleware('auth:web')->group(function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::get('home', 'BMIController@home');
    Route::get('location', function () {
        return view('location');
    });
    Route::get('statistic_person', function () {
        return view('statistic_person');
    });
    Route::get('statistic_overview', function () {
        return view('statistic_overview');
    });

    Route::get('get_location','BMIController@getLocation');
    Route::get('get_location/{id}','BMIController@getLocationById');
    Route::get('get_location_list','BMIController@getAllLocation');
    Route::get('get_bmi_user','BMIController@getBmiUser');
    Route::get('get_static_overview','BMIController@getStaticOverview');
});


Route::post('/login', 'Auth\LoginController@login');
Route::get('/login', 'Auth\LoginController@showLoginForm');
Route::get('/logout', 'Auth\LoginController@logout');