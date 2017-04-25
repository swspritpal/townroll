<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{


    public function likePost($id)
    {
        // here you can check if post exists or is valid or whatever

        $this->handleLike('App\Post', $id);
        return redirect()->back();
    }

    public function handleLike($type, $id)
    {
        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            $like_model=Like::create([
                'user_id'       => Auth::id(),
                'likeable_id'   => $id,
                'likeable_type' => $type,
            ]);

            $postData=\App\Post::whereId($id)->first();
            $notifyTo=$postData->user_id;

            // when some other user likes post then notify to Auther 
            if($notifyTo != \Auth::id()){
                $user_notification=\FeedManager::getUserFeed(\Auth::user()->id);

                // Push notification for Stream about the like action
                $data = [
                    "actor"=>"\App\Models\Access\User\User:".\Auth::user()->id,
                    "verb"=>"like",
                    "object"=>"\App\Post:".$id,
                    "foreign_id"=>"\App\Like:".$like_model->id,
                    "is_read" => false,
                    "is_seen" => false,
                    'to' => ['notification:'.$notifyTo],
                ];
                $user_notification->addActivity($data);
            }
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
    }

    /**
     * Show liked post users list
     *
     * @param  Request $request
     * @return Response
     */
    public function likedUsersList(Request $request,$post_id)
    {

        $html_result='';

        $liked_users=Like::where('likeable_id', $post_id)
                ->with(['users'=>function($user_query){
                	$user_query->select('users.id','username','profile_uri');
                }])
                ->orderBy('created_at','desc')
                ->selectRaw('`likes`.*,(select count(*) from `users` inner join `likes` on `users`.`id` = `likes`.`user_id` where  `likes`.`likeable_id` = '.$post_id.' and `users`.`deleted_at` is null) as `users_count`')

                //->limit(env('DEFAULT_HOME_PAGE_LIKED_USERS_LIMIT'))
                //->get();
                ->paginate(env('DEFAULT_HOME_PAGE_LIKED_USERS_LIMIT'));
                //->toSql();

        //dd($liked_users);

        if(!empty($liked_users)){

            $view = \View::make('frontend.includes.posts.liked_list',compact('liked_users'));
            $html_result = $view->render();
        }else{
            $html_result = 'users not found ';
        }

        return response()->json(['status' => 200, 'message' => 'success','html_result'=>$html_result]);
    }

}
