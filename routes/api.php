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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('field/list', 'Api\FieldController@list');
    Route::post('field/add', 'Api\FieldController@create');
    Route::put('field/update/{id}', 'Api\FieldController@update');
    Route::delete('field/remove/{id}', 'Api\FieldController@remove');

    Route::get('subscriber/list', 'Api\SubscriberController@list');
    Route::get('subscriber/show/{id}', 'Api\SubscriberController@show');
    Route::post('subscriber/create', 'Api\SubscriberController@create');
    Route::put('subscriber/update/{id}', 'Api\SubscriberController@update');
    Route::delete('subscriber/remove/{id}', 'Api\SubscriberController@remove');
});