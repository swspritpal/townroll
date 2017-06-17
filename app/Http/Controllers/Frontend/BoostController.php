<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Post;
use App\Category;

class BoostController extends Controller
{




    /**
     * @return \Illuminate\View\View
     */
    public function create(Request $request,$post_id=null,$search_key=null)
    {
    	$categories = Category::whereHas('users', function ($query) {
            $query->where('user_id', '=', \Auth::user()->id);
        })->withCount('users');

    	if(!empty($search_key)){
    		$categories->where('name', 'like', '%' . $search_key . '%');
    	}
    	$categories=$categories->paginate(env('DEFAULT_BOOST_CATEGORY_PAGINATION_LIMIT_ON_POPUP'));

        $view = \View::make('frontend.includes.popups.post-boost-ajax',compact('categories','search_key','post_id'));
        return $view->render();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
    	$status=$message='';

    	$categories_to_boost=$request->get('post_boost_groups');
    	$boost_unchecked_categories=$request->get('boost_unchecked_categories');

    	$post_id=$request->get('boost_post_id');
    	$user_id=$request->get('user_id');    	

    	if( (!empty($categories_to_boost) || !empty($boost_unchecked_categories) ) && !empty($post_id) && !empty($user_id)){    		

    		$boost_post=\App\BoostPost::whereUserId($user_id)->wherePostId($post_id)->first();
    		if(is_null($boost_post)){
    			$boost_post=new \App\BoostPost;
	    		$boost_post-> user_id =$user_id;
	    		$boost_post-> post_id =$post_id;
	    		$boost_post->save();
    		} 		

    		if($boost_post){

    			// Delete previous category If UNCHECKED 
		    	if(!empty($boost_unchecked_categories)){
		    		$boost_unchecked_categories=explode(",", $boost_unchecked_categories);

	    			foreach ($boost_unchecked_categories as $boost_unchecked_category) {
	    				$delete_category=\App\BoostPostCategories::whereCategoryId($boost_unchecked_category)->whereBoostPostId($boost_post->id)->first();

			    		if(!empty($delete_category)){			    			

		    				$is_deleted=$delete_category->delete();
		    				// Not delete for some reson not works then write log 
		    				if($is_deleted == false){
		    					\Log::error('Boost Delete not working. Kindly check it. Ids : '.$boost_unchecked_category.' Connected User and post  '.$boost_post->id);
		    				}
			    		}
	    			}
	    		}

	    		// Add new categories
    			if(!empty($categories_to_boost)){
	    			foreach ($categories_to_boost as $category) {
	    				$is_category_already_related_to_user=\App\BoostPostCategories::whereCategoryId($category)->whereBoostPostId($boost_post->id)->first();

	    				// ensure to check Category is not already exit in table with repected User
	    				if(is_null($is_category_already_related_to_user)){
	    					$boost_post_category=new \App\BoostPostCategories;
		    				$boost_post_category-> boost_post_id=$boost_post->id;
		    				$boost_post_category-> category_id=$category;	
		    				$boost_post_category->save();
	    				}		    			
		    		}
		    	}
	    		$status='success';
    			$message='Your post boosted saved successfully.';
    			
    		}else{
    			$status='error';
    			$message='There was some error while boosting post. Please try again.';
    		}
    	}else{
    		$status='error_validate';
    		$message='You must select at least one group to boost this post.';
    	}
    	
    	return response()
                ->json(['status' => $status,'message'=>$message,'post_id'=>$post_id]);
    }


    /**
     * @return \Illuminate\View\View
     */
    public function boostedUsers()
    {
        return view('frontend.macros');
    }
}
