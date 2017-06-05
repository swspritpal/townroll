<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Access\User\User;
use App\Models\Access\User\SocialLogin;
use App\Post;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GetStream\StreamLaravel\Enrich;


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
            $previous_posts_on_certain_time= \Carbon\Carbon::now()->subHours(env('DEFAULT_RECENT_POST_MERGE_IN_HOURS'))->toDateTimeString();
            $excluded_recent_posts=[];

            $login_user_categoreis_wise_posts = Post::
                whereIn('id', function($category_post_query) use($sort_by){
                    $category_post_query
                        ->select('post_id')
                        ->from(with(new \App\CategoryPost)->getTable())
                        ->whereIn('category_id',function($category_query) use($sort_by){
                                $category_query
                                    ->select(['category_id'])
                                    ->from(with(new \App\CategoryUser)->getTable())
                                    ->where('user_id', '=', \Auth::id());

                                if(!empty($sort_by)){
                                    $category_query->where('category_id', '=', $sort_by['cat']);
                                }
                                $category_query->get()
                                ->toArray();
                        })
                        ->get()
                        ->toArray();

                })
                ->where('created_at','>=',$previous_posts_on_certain_time)
                
                ->groupBy(['posts.user_id','posts.id'])
                ->orderBy('created_at', 'desc')
                //->toSql();
                ->get(['posts.id','posts.user_id'])
                ->toArray();

            $last_posts_ids_by_particular_user = array_column($login_user_categoreis_wise_posts,'id','user_id');
            $all_recent_posts_only_ids=array_column($login_user_categoreis_wise_posts,'id');
            $excluded_recent_posts=array_diff($all_recent_posts_only_ids,$last_posts_ids_by_particular_user);


            $posts = Post::with(
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
                // posts add by joined group member
                ->whereIn('id', function($category_post_query) use($sort_by){
                    $category_post_query
                        ->select('post_id')
                        ->from(with(new \App\CategoryPost)->getTable())
                        ->whereIn('category_id',function($category_query) use($sort_by){
                                $category_query
                                    ->select(['category_id'])
                                    ->from(with(new \App\CategoryUser)->getTable())
                                    ->where('user_id', '=', \Auth::id());

                                if(!empty($sort_by)){
                                    $category_query->where('category_id', '=', $sort_by['cat']);
                                }
                                $category_query->get()
                                ->toArray();
                        })
                        ->get()
                        ->toArray();
                })
                
                // Boosted posts from other group member
                ->orWhereIn('id', function($boost_post_query) use($sort_by){
                    $boost_post_query
                        ->select('post_id')
                        ->distinct('user_id')
                        ->from(with(new \App\BoostPost)->getTable())
                         ->whereIn('id',function($boost_post_category_query) use($sort_by){
                            $boost_post_category_query
                                ->select(['boost_post_id'])
                                ->distinct('category_id')
                                ->from(with(new \App\BoostPostCategories)->getTable())
                                ->whereIn('category_id',function($select_user_category_query){
                                    $select_user_category_query
                                        ->select(['category_id'])
                                        ->from(with(new \App\CategoryUser)->getTable())
                                        ->whereUserId(\Auth::id());
                                });

                            if(!empty($sort_by)){
                                $boost_post_category_query->where('category_id', '=', $sort_by['cat']);
                            }
                            $boost_post_category_query->get()
                            ->toArray();
                        })
                    ->get()
                    ->toArray();
                })
               

                // merge recent post logic
                ->whereNotIn('id',$excluded_recent_posts)

                ->orderBy('posts.created_at', 'desc')
                //->toSql();
                /*->limit(env('DEFAULT_HOME_PAGE_POST'))
                ->get();*/
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
