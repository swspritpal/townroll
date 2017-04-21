<?php

/**
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', 'LanguageController@swap');

/* ----------------------------------------------------------------------- */

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    includeRouteFiles(__DIR__.'/Frontend/');
});

/* ----------------------------------------------------------------------- */

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    includeRouteFiles(__DIR__.'/Backend/');
});







Route::get('config',function(){
	dd(config('services'));
});


Route::get('/placeautocomplete', function () {
    $response = \GoogleMaps::load('placeautocomplete')
        ->setParam ([
            'input' =>'rajpura,punjab,in',
            //'location' =>'30.7039652, 76.6816849',
            //'location' =>'30.483997,76.593952',
            'type' =>'geocode',
            'components' =>['country'=>'in'],
            ])
        ->get();

    dd(json_decode($response));
});

/**
 * Selected for CITY group
 */
Route::get('/geo', function () {
    $response = \GoogleMaps::load('geocoding')
        ->setParam ([
            'input' =>'mohali,in',
            'location' =>'30.7039652,76.6816849',
            'bounds' =>'LatLngBounds',
            'components' =>'country:in',
            'region' =>'locality',
            'type' =>'geocode',         
            ])
        ->get();

    dd(json_decode($response));
});

Route::get('/placephoto', function () {
    $data = \GoogleMaps::load('placephoto')
        ->setParam ([
            'photoreference' =>'CoQBdwAAAN425JhBSY1_ZnUnsk42CVE3qyzJ8bZoxDNSLxqNEXkSRlF09UyzibD1Gfxj3FHhmq4ljjhq85Z3EPcW7xlkrNAUad0qWcJJtfYQP0i01qTXjqJpKxjq42A2qAQucQxNtAD2TZjpEfrPEMptz2frGpQ9GxCDmKUMqIgIRCriTiYBEhBKSyOHQi-CE4dqWItyZmV5GhRxnPrZViTDLeuaecIuUhQ8OE6tYg',

            //'photoreference' =>'CoQBdwAAANRRqfF5g0GV6DtNfddLtsZQO_sCjvoKbY86BJf34RJBW4cl-nr3qEPFG88S_jCH_ztdnUwUqU3SC9ucGynQCaObQt14TO6qedOLcv_--mXeDdybdROjTWK-cOPV3pI9vYetsb9TJpH2Sjg5HLLWiEKK958_67IJOX4SWwGX7PvoEhDbA9r7DtHGBRshZ-c3jdwmGhSbIAyP4PTWHPLVwt56ZUPDwD7hoQ',
            
            'maxwidth'=>200,
            ])
        ->get();
    $image_src = 'data:image/PNG;base64,' . base64_encode($data);
    echo '<img src="'.$image_src.'">';
});


Route::get('/placedetails', function () {
    $response = \GoogleMaps::load('placedetails')
        ->setParam ([
            'placeid' =>'ChIJkbeSa_BfYzARphNChaFPjNc',
            ])
        ->get();

    dd(json_decode($response));
});


Route::get('/nearbysearch', function () {
    $response = \GoogleMaps::load('nearbysearch')
        ->setParam ([
            //'location' =>'-33.8670,151.1957',
            //'location' =>'30.704649, 76.717873',
            'location' =>'30.7039652, 76.6816849',
            'radius' =>'1000',
            ])
        ->get();

    dd(json_decode($response));
});

