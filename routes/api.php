<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@login');
Route::post('resetPassword', 'Api\PasswordResetController@create');
Route::Post('passwordRest','Api\UserController@sendPasswordRestEmail');
Route::Post('updatePassword','Api\UserController@updatePassword');
Route::post('socailRegister', 'Api\UserController@socailRegister');

Route::middleware('auth:api')->group( function () {
    Route::get('details', 'Api\UserController@details');
    Route::get('userData','Api\UserController@userData');
    Route::post('verify', 'Api\UserController@verify');
    Route::post('changePassword','Api\UserController@changePassword');
    Route::get('getAds','Api\UserDataController@getAds');
    Route::post('attandance','Api\UserDataController@attandance');
});
