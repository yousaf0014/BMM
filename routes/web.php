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
Route::get('/', function () {
    return redirect('/login');
});
Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::get('/uniqeEmail', 'HomeController@uniqeEmail');
Route::get('cookie', 'CommonController@cookie');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('info/{slug}','HomeController@userForms');
Route::Post('saveUserForm/{slug}','HomeController@saveUserData');

Route::group(['middleware' =>['auth','web']],function(){
    Route::get('/dashboard','HomeController@adminDashboard')->name('adminDashboard');
    Route::get('/adminDashboard','HomeController@adminDashboard')->name('adminDashboard');
    Route::get('/clientDashboard','HomeController@clientDashboard')->name('clientDashboard');
    Route::get('/shopDashboard','HomeController@shopDashboard')->name('shopDashboard');
    Route::post('/actAs','HomeController@actAs');
    Route::get('/backToOrignal','HomeController@backToOrignal');
    Route::get('/attendanceAjax','HomeController@attendanceAjax');
    Route::post('/viewBuilding','HomeController@viewBuilding');
    Route::get('/clientdashboardBuilding','HomeController@clientdashboardBuilding');
    
    Route::get('/users','UserController@index');
    Route::get('/users/create','UserController@create');
    Route::get('/users/{user}','UserController@show');
    Route::get('/users/{user}/edit','UserController@edit');
    Route::get('/users/{user}/assignBuildings/','UserController@assignBuildings');
    Route::get('/users/{user}/assignshop/','UserController@assignshop');
    Route::get('/getZones','HomeController@getZones');
    Route::post('/users','UserController@store');
    Route::post('users/storeBuilding/{user}','UserController@storeBuilding');
    Route::post('users/storeShop/{user}','UserController@storeShop');
    Route::put('/users/update/{user}','UserController@update');
    Route::delete('/users/{user}','UserController@delete');

    Route::get('/roles','RoleController@index');
    Route::get('/roles/create','RoleController@create');
    Route::get('/roles/{role}','RoleController@show');
    Route::get('/roles/{role}/edit','RoleController@edit');
    Route::post('/roles','RoleController@store');
    Route::put('/roles/update/{role}','RoleController@update');
    Route::delete('/roles/{role}','RoleController@delete');

    Route::get('/buildings','BuildingsController@index');
    Route::get('/buildings/create','BuildingsController@create');
    Route::get('/buildings/{building}','BuildingsController@show');
    Route::get('/buildings/{building}/edit','BuildingsController@edit');
    Route::post('/buildings','BuildingsController@store');
    Route::put('/buildings/update/{building}','BuildingsController@update');
    Route::delete('/buildings/{building}','BuildingsController@delete');

    Route::get('signup', 'Auth\RegisterController@getsignup');
    Route::post('signup', 'Auth\RegisterController@postsignup');
    Route::get('/home', 'HomeController@index');
    
    Route::get('/beacons','BeaconController@index');
    Route::get('/beacons/create','BeaconController@create');
    Route::get('/beacons/{beacon}','BeaconController@show');
    Route::get('/beacons/{beacon}/edit','BeaconController@edit');
    Route::get('/beacons/active/{beacon}/{active}','BeaconController@active');
    Route::post('/beacons','BeaconController@store');
    Route::put('/beacons/update/{beacon}','BeaconController@update');
    Route::delete('/beacons/{beacon}','BeaconController@delete');

    Route::get('/zones','ZonesController@index');
    Route::get('/zones/create','ZonesController@create');
    Route::get('/zones/{zone}','ZonesController@show');
    Route::get('/zones/{zone}/edit','ZonesController@edit');
    Route::post('/zones','ZonesController@store');
    Route::put('/zones/update/{zone}','ZonesController@update');
    Route::delete('/zones/{zone}','ZonesController@delete');

    Route::get('/ads','AdsController@index');
    Route::get('/ads/create','AdsController@create');
    Route::get('/ads/getBeacons','AdsController@getBeacons');
    Route::get('/ads/{ad}','AdsController@show');
    Route::get('/ads/{ad}/edit','AdsController@edit');
    Route::post('/ads','AdsController@store');
    Route::put('/ads/update/{ad}','AdsController@update');
    Route::delete('/ads/{ad}','AdsController@delete');

    Route::get('/messages','AdsController@index1');
    Route::get('/messages/create','AdsController@create1');
    Route::get('/messages/{ad}','AdsController@show1');
    Route::get('/messages/{ad}/edit','AdsController@edit1');
    Route::post('/messages','AdsController@store1');
    Route::put('/messages/update/{ad}','AdsController@update1');
    Route::delete('/messages/{ad}','AdsController@delete1');
});
