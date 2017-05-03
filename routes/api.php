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

	    Route::get('user/check', 'UserController@check');

	    
	    Route::get('get-countires', 'CommonController@get_countries');
	    Route::get('get-states/{country_sortname}', 'CommonController@getStates');
	    Route::get('get-cities/{state_id}', 'CommonController@getCities');
	    Route::post('unique-username/', 'CommonController@unique_username');


	    Route::get('posts/{user_id}/{sort_by?}', 'PostController@index');
	    Route::get('categories/{user_id}', 'CategoriesController@index');

	    Route::post('login', 'UserController@login');
	    Route::resource('user', 'UserController');
	    Route::post('user/signup', 'UserController@signUp');
	    Route::get('user-counter-data/{user_id}', 'UserController@getUserCountData');


	    // Image upload Route
	    Route::post('upload-image', 'CommonController@upload_image');
	});
});


