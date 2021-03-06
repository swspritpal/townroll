<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
//Route::get('/{place?}/{id?}', 'FrontendController@index')->name('index');
Route::get('/', 'FrontendController@index')->name('index');
Route::get('macros', 'FrontendController@macros')->name('macros');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {

    Route::get('@{username}','User\ProfileController@userProfile')->name('auth.user.profile');
    Route::get('profile-popup','User\ProfileController@popup')->name('auth.user.profile-popup');
    Route::post('save-profile-image','User\ProfileController@save_profile_image')->name('auth.user.save-profile-image');


    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', 'AccountController@index')->name('account');

        /*
         * User Profile Specific
         */
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
        
        Route::post('signup', 'ProfileController@signUp')->name('profile.signup');
    });
});
