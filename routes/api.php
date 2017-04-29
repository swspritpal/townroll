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


Route::group(array('middleware' => 'auth:api'), function() {
    //Route::resource('restful-apis','APIController');    

    Route::get('/user', function (Request $request) {
	    return $request->user();
	});
	
	Route::group(['namespace' => 'Api'], function () {

	    Route::post('login', 'UserController@login');
	    Route::get('user/check', 'UserController@check');
	    Route::resource('user', 'UserController');
	});
});


