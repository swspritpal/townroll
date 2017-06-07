<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Country\Country;
Use App\Models\Country\State;
Use App\Models\Country\City;
use App\Models\Access\User\User;

use Illuminate\Support\Facades\Auth;



class SearchController extends Controller
{

    public function __construct()
    {
        $this->content = array();
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function findUser(Request $request)
    {   
        $q=$request->get('q');
        return User::search($q,null,$fulltextSearch=true)->limit(env('DEFAULT_SEARCH_SUGGESTION_PAGINATION'))->get();
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function findGroup(Request $request)
    {   
        $q=$request->get('q');
        return \App\Category::search($q,null,$fulltextSearch=true)->limit(env('DEFAULT_SEARCH_SUGGESTION_PAGINATION'))->get(['categories.name']);
    }


    /**
     * @return \Illuminate\Response\Html
     */
    public function search(Request $request)
    {           
        $q=$request->get('q');
        if(!empty($q)){

            $state_id_of_login_user=\Auth::user()->state_id;

            $categories=\App\Category::select(['categories.name','categories.place_image_path'])->withCount('users')->search($q,null,$fulltextSearch=true)->paginate(env('DEFAULT_SEARCH_PAGE_PAGINATION'));

            $users=User::search($q,null,$fulltextSearch=true)->paginate(env('DEFAULT_SEARCH_PAGE_PAGINATION'));

            
            // We need state wise post related to login user
            $posts=\App\Post::whereHas('user', function($q) use($state_id_of_login_user)
                {
                    $q->where('state_id',$state_id_of_login_user);

                })
                ->with(
                    [
                        'user',
                        'categories',
                        'boosts',
                    ]
                )
                ->withCount('comments')
                ->withCount('likes')
                ->withCount('views')
                ->withCount('slaps')
                ->search($q,null,$fulltextSearch=true)
                
                ->paginate(env('DEFAULT_SEARCH_PAGE_PAGINATION'));
                //->toSql();

            //dd($posts);

            return view('frontend.search.index',compact('categories','users','posts','q'));
        }        
    }

}
