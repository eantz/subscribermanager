<?php

use Illuminate\Http\Request;

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

Route::post('auth/login', 'Api\AuthController@login');

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', 'Api\UserController@show');

    Route::get('field/list', 'Api\FieldController@list');
    Route::post('field/create', 'Api\FieldController@create');
    Route::put('field/update/{id}', 'Api\FieldController@update');
    Route::delete('field/remove/{id}', 'Api\FieldController@remove');

    Route::get('subscriber/list', 'Api\SubscriberController@list');
    Route::get('subscriber/show/{id}', 'Api\SubscriberController@show');
    Route::post('subscriber/create', 'Api\SubscriberController@create');
    Route::put('subscriber/update/{id}', 'Api\SubscriberController@update');
    Route::post('subscriber/unsubscribe/{id}', 'Api\SubscriberController@unsubscribe');
    Route::delete('subscriber/remove/{id}', 'Api\SubscriberController@remove');
});