<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{

    public function __construct()
    {
        $this->content = array();
    }

    public function get_countries(){

		$countries = getCountiesList();

    	if(!empty($countries)){
            $this->content['error'] = false;
            $this->content['massage'] = "Countries list.";
            $this->content['data'] = $countries;
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "Countries not found.";
            $this->content['data'] ='';
            $status = 200;
        }
        return response()->json($this->content, $status);
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function getStates($country_sortname)
    {   
        $country_id=getCountryId($country_sortname);

        $states = getStateList($country_id);

        if(!empty($states)){
            $this->content['error'] = false;
            $this->content['massage'] = "states list.";
            $this->content['data'] = $states;
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "Countries not found.";
            $this->content['data'] ='';
            $status = 200;
        }
        return response()->json($this->content, $status);
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function getCities($state_id)
    {   

        $cities = getCityList($state_id);

        if(!empty($cities)){
            $this->content['error'] = false;
            $this->content['massage'] = "city list.";
            $this->content['data'] = $cities;
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "Countries not found.";
            $this->content['data'] ='';
            $status = 200;
        }
        return response()->json($this->content, $status);
    }


    /**
     * @return \Illuminate\Response\Json
     */
    public function unique_username(Request $request)
    {   
        $username=$request->input('username');
        if(!empty($username)){
            $response = is_username_unique($username);
            if($response == true){
                $this->content['error'] = false;
                $this->content['massage'] = "Username already taken.";
                $this->content['data'] =false;
                $status = 200;
            }else{     
                $this->content['error'] = false;
                $this->content['massage'] = "Username unique.";
                $this->content['data'] = true;
                $status = 200;           
                
            }

        }else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }
            
        return response()->json($this->content, $status);
    }
    
}
