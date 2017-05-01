<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Category;

class CategoriesController extends Controller
{

    public function __construct()
    {
        $this->content = array();
    }

    public function index($user_id){

    	if(!empty($user_id)){
    		$categories = Category::whereHas('users', function ($query) use($user_id) {
	                $query->whereUserId($user_id);
	            })
	            ->withCount('users')->paginate(env('DEFAULT_HOME_PAGE_PLACES_FOR_APP'))->toArray();

        	if(!empty($categories)){
                $this->content['error'] = false;
                $this->content['massage'] = "User have categories.";
                $this->content['data'] = $categories;
                $status = 200;
            }else{
                $this->content['error'] = false;
                $this->content['massage'] = "Categoires not found.";
                $this->content['data'] ='';
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
