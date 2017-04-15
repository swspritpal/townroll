<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Access\User\User;
use App\Models\Access\User\SocialLogin;
use App\Post;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class FrontendController.
 */
class FrontendController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sort_by=[];

        if($request->has('cat')){
            $sort_by['cat']=$request->get('cat');
        }

        if(\Auth::guest()){
            return view('frontend.auth.login');
        }else{
            $posts = Post::with(
                    [
                        'user',
                        'categories' /*=> function($cat_q){
                            $cat_q->whereHas('users', function ($query) {
                                $query->where('user_id', '=', \Auth::user()->id);
                            });
                        },*/

                        /*'comments'=>function($comment_q){
                            $comment_q->with('user')
                                ->orderBy('created_at', 'desc')
                                ->limit(env('DEFAULT_HOME_PAGE_POST_COMMENTS'))
                                ->get();
                        },*/
                    ]
                )
                ->where('status','=','1')
                ->orderBy('created_at', 'desc')
                ->withCount('comments')

                ->whereIn('id', function($category_post_query) use($sort_by){
                    $category_post_query
                        ->select('post_id')
                        ->from(with(new \App\CategoryPost)->getTable())
                        ->whereIn('category_id',function($category_query) use($sort_by){
                                $category_query
                                    ->select(['category_id'])
                                    ->from(with(new \App\CategoryUser)->getTable())
                                    ->where('user_id', '=', \Auth::user()->id);

                                if(!empty($sort_by)){
                                    $category_query->where('category_id', '=', $sort_by['cat']);
                                }
                                $category_query->get()
                                ->toArray();
                        })
                        ->get()
                        ->toArray();

                })
                //->toSql();
                ->paginate(env('DEFAULT_HOME_PAGE_POST'));

            //dd($posts);
            return view('frontend.index',compact('posts','sort_by','request'));
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function macros()
    {
        return view('frontend.macros');
    }
}