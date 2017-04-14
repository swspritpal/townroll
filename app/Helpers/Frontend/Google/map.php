<?php

/**
 * Google Map API helpers
 */



/**
 *  Geo Coding
 * @required Params Integer $input
 */
if (!function_exists('geocoding')) {

    function geocoding($input=null,$location=null,$country=null)
    {
        $params=[
            'input' =>get_city_name($input).','.strtolower($country),
            'bounds' =>'LatLngBounds',
            'region' =>'locality',
            'type' =>'geocode',
            ];

        if(!empty($location)){
            $params['location'] =$location;
        }     
        if(!empty($country)){
            $params['components'] ='country:'.$country;
        }

        $response = \GoogleMaps::load('geocoding')
                ->setParam ($params)
                ->get();

        return json_decode($response,true);
    }
}


if (!function_exists('placedetails')) {

    function placedetails($place_id=null)
    {
        $response = \GoogleMaps::load('placedetails')
            ->setParam ([
                'placeid' =>$place_id,
                ])
            ->get();

        return json_decode($response,true);
    }
}

if (!function_exists('placephoto')) {

    function placephoto($photoreference=null,$place_id)
    {
         $google_image_data = \GoogleMaps::load('placephoto')
            ->setParam ([
                'photoreference' =>$photoreference,                
                'maxwidth'=>500,
                ])
            ->get();

        $data = 'data:image/PNG;base64,' . base64_encode($google_image_data);

        // save Raw image data into server

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        $is_saved=file_put_contents(public_path().'/img/goole_places_image/'.$place_id.'.png', $data);

        if (!empty($is_saved))
            return $place_id.'.png';
        else
            return "error";
        
    }
}

if (!function_exists('nearbysearch')) {

    function nearbysearch($location=null,$radius=500)
    {
        $response = \GoogleMaps::load('nearbysearch')
        ->setParam ([
            'location' =>$location,
            'radius' =>$radius,
            ])
        ->get();

        return json_decode($response,true);        
    }
}




    


