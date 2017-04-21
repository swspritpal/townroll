<?php

Route::group(['middleware' => 'admin'], function () {
   Route::get('/api-dashboard', function () {
       return View::make('frontend.api');
    });
});
