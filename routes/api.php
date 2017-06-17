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

    Route::get('/user', function (Request $request) {
	    return $request->user();
	});
	
	Route::group(['namespace' => 'Api'], function () {

	    //Route::get('user/check', 'UserController@check');

	    
	    Route::get('get-countires', 'CommonController@get_countries');
	    Route::get('get-states/{country_sortname}', 'CommonController@getStates');
	    Route::get('get-cities/{state_id}', 'CommonController@getCities');
	    Route::post('unique-username/', 'CommonController@unique_username');


	    Route::get('posts/{user_id}/{sort_by?}', 'PostController@index');
	    Route::post('post/store', 'PostController@store');
	    Route::get('categories/{user_id}', 'CategoriesController@index');
	    Route::post('categories/store', 'CategoriesController@store');

	    Route::post('login', 'UserController@login');
	    Route::resource('user', 'UserController');
	    Route::post('user/signup', 'UserController@signUp');
	    Route::get('user-counter-data/{user_id}', 'UserController@getUserCountData');


	    // Image upload Route
	    Route::post('upload-image', 'CommonController@upload_image');

	    Route::post('comment/store', 'CommonController@commentStore');
	    Route::get('comments/{commentable_id}/{commentable_type}', 'CommonController@comments');


	    Route::get('is-post-liked-by-user/{post_id}/{user_id}', 'PostController@is_post_liked_by_user');
	    Route::get('is-post-slapped-by-user/{post_id}/{user_id}', 'PostController@is_post_slapped_by_user');
	    Route::get('post-viewed-or-newly-view/{post_id}/{user_id}', 'PostController@post_viewed_or_newly_view');
	});


	// These are common controllers for WEB and API 
	Route::get('post-liked-users/{post_id}', 'Frontend\LikeController@likedUsersList');
	Route::get('post/like/{id}/{user_id}', 'Frontend\LikeController@likePost');
	Route::get('post-slapped-users/{post_id}', 'Frontend\SlapController@slappedUsersList');
	Route::get('post/slap/{id}/{user_id}', 'Frontend\SlapController@slapPost');
	Route::get('post-viewed-users/{post_id}', 'Frontend\Post\PostController@viewedUsersList');


	Route::post('save-user-profile-image', 'Frontend\User\ProfileController@save_profile_image');

});


