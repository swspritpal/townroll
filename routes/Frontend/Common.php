<?php
/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {
    
    Route::get('near-by-places', 'CommonController@findNearPlaces')->name('findnearplaces');

    Route::get('states/{country_sortname}', 'CommonController@getStates')->name('getstates');
    Route::get('cities/{state_id}', 'CommonController@getCities')->name('getcities');
    Route::post('unique-username', 'CommonController@unique_username')->name('unique_username');

    Route::get('mark-notification-read', 'CommonController@markNotificationRead')->name('unique_username');

    Route::get('suggest-user-list', 'CommonController@suggest_user_list');

    // Search
    Route::get('search', 'SearchController@search')->name('search');
    Route::get('search/posts', 'SearchController@searchPosts')->name('search.posts');
    Route::get('search/users', 'SearchController@searchUsers')->name('search.users');
    Route::get('search/categories', 'SearchController@searchCategories')->name('search.categories');
    // Search Suggestion Routes
    Route::get('find-user', 'SearchController@findUser');
    Route::get('find-group', 'SearchController@findGroup');
    
});
