<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Access\Category\CategoryRepository;
use Response;
use App\Category;

class CategoriesController extends Controller
{

    protected $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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


    public function store(Request $request){

        $user_id=$request->get('user_id');
        
        if(!empty($user_id)){
            $category = $this->categoryRepository->add_new($request);

            if(!empty($category)){
                // Attach user id to category model
                $category->users()->attach($user_id);

                $this->content['error'] = false;
                $this->content['massage'] = "category_added_successfully.";
                $status = 200;
            }else{
                $this->content['error'] = true;
                $this->content['massage'] = "category_not_saved.";
                $status = 500;
            }
        }else{
            $this->content['massage'] = "invalid_params";
            $this->content['error'] = true;
            $status = 500;
        }
        
        return response()->json($this->content, $status);
    }
}
