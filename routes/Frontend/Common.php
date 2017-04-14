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
    
});
