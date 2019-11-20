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
    Route::get('profile', function () {
        return view('profile');
    });
    Route::get('location-admin', function () {
        return view('locationadmin');
    });
    Route::get('site-admin', function () {
        return view('siteadmin');
    });
    Route::get('board-admin', function () {
        return view('boardadmin');
    });
    Route::get('hardware-admin', function () {
        return view('hardwareadmin');
    });
    Route::get('statistic_person', function () {
        return view('statistic_person');
    });
    Route::get('statistic_overview', function () {
        return view('statistic_overview');
    });
    Route::get('get_report','BMIController@getReport');

    Route::get('get_profile','BMIController@getProfile');
    Route::post('save_profile','BMIController@saveProfile');
    Route::get('get_status','BMIController@getStatus');
    Route::get('get_hw/{id}','BMIController@getHwById');
    Route::post('save_hw','BMIController@SaveHW');

    Route::get('get_location_user','BMIController@getLocationUser');
    Route::get('get_location_admin','BMIController@getLocationAdmin');

    Route::get('get_board_admin','BMIController@getBoardAdmin');
    Route::get('get_board/{id}','BMIController@getBoardById');
    Route::get('get_site_list','BMIController@getAllSite');
    Route::post('save_board','BMIController@SaveBoard');

    Route::get('get_site_admin','BMIController@getSiteAdmin');
    Route::get('get_site/{id}','BMIController@getSiteById');
    Route::post('save_site','BMIController@SaveSite');

    Route::get('get_location_list','BMIController@getAllLocation');
    Route::get('get_location/{id}','BMIController@getLocationById');
    Route::post('save_location','BMIController@SaveLocation');


    Route::get('get_location_admin','BMIController@getLocationAdmin');


    Route::get('get_bmi_user','BMIController@getBmiUser');
    Route::get('get_static_overview','BMIController@getStaticOverview');
    Route::get('get_chart_bmi','BMIController@getChartBMI');
});


Route::post('/login', 'Auth\LoginController@login');
Route::get('/login', 'Auth\LoginController@showLoginForm');
Route::get('/logout', 'Auth\LoginController@logout');
