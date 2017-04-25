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

            /*$user_feed_1 = \FeedManager::getUserFeed(\Auth::id());
            $token = $user_feed_1-> getToken();
            dd($token);*/

            /*$notification_feed = \FeedManager::getNotificationFeed(\Auth::id());
            $enricher = new Enrich;
            $notifications = $notification_feed->getActivities(0,25)['results'];

            if(!empty($notifications)){

                $enricher = new Enrich;
                $notifications = $enricher->enrichActivities($notifications['0']['activities']);
                dd($notifications);

                if(!empty($notifications)){
                    echo '<ul>';
                    foreach($notifications as $notification){
                        if(is_array($notification) || $notification->enriched()){
                            echo '<li>'.$notification['actor']->username.' '.$notification['verb'].'s your post'.'</li>';
                        }
                    }
                }
            }*/
            


            /*$client=\FeedManager::getClient();
            //$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));

            $activity = [
                "actor" => "\App\Models\Access\User\User:1",
                "foreign_id" => "\App\Like:9",
                "id" => "9d05d50a-2984-11e7-8080-8001577009ab",
                "is_read" => true,
                "is_seen" => true,
                "object" => "\App\Post:76",
                "origin" => null,
                "target" => null,
                "time" => "2017-04-25T06:58:40.491649",
                "to" => [0 => "notification:17"],
                "verb" => "like",
            ];
            dd($client->updateActivity($activity));*/
            
           

            /*$notification_feed = \FeedManager::getUserFeed(\Auth::id());
            $enricher = new Enrich;
            $notification_feed = $notification_feed->getActivities(0,25)['results'];
            $notification_feed = $enricher->enrichActivities($notification_feed);*/

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
