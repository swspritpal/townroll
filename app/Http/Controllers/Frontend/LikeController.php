<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{

    public function __construct()
    {
        $this->content = array();
    }

    public function likePost(Request $request,$id,$user_id=null)
    {
        // here you can check if post exists or is valid or whatever
        if(!empty($user_id)){
            $this->handleLike('App\Post', $id,$user_id);
        }else{
            $this->handleLike('App\Post', $id,Auth::id());
        }
        
        // For web response
        if($request->is('api/*') == false){
            return redirect()->back();
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "success";
            $status = 200;
            return response()->json($this->content, $status);
        }
        
    }

    public function handleLike($type, $id=null,$user_id=null)
    {
        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId($user_id)->first();

        if (is_null($existing_like)) {
            $like_model=Like::create([
                'user_id'       => $user_id,
                'likeable_id'   => $id,
                'likeable_type' => $type,
            ]);

            $postData=\App\Post::whereId($id)->first();
            $notifyTo=$postData->user_id;

            // when some other user likes post then notify to Auther 
            if($notifyTo != $user_id){
                $user_notification=\FeedManager::getUserFeed($user_id);

                // Push notification for Stream about the like action
                $data = [
                    "actor"=>"\App\Models\Access\User\User:".$user_id,
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
                ->with(['user'=>function($user_query){
                	$user_query->select('users.id','username','profile_uri','profile_image');
                }])
                ->orderBy('created_at','desc')
                //->selectRaw('`likes`.*,(select count(*) from `users` inner join `likes` on `users`.`id` = `likes`.`user_id` where  `likes`.`likeable_id` = '.$post_id.' and `users`.`deleted_at` is null) as `users_count`')
                ->paginate(env('DEFAULT_HOME_PAGE_VIEWED_OR_LIKED_USERS_LIMIT'));
                //->toSql();

        // For web response
        if($request->is('api/*') == false){
            if(!empty($liked_users)){
                $view = \View::make('frontend.includes.posts.viewed_or_liked_list',['viewed_or_liked_users'=>$liked_users]);
                $html_result = $view->render();
            }else{
                $html_result = 'users not found ';
            }
            return response()->json(['status' => 200, 'message' => 'success','html_result'=>$html_result]);
        }else{

            if(!empty($liked_users)){
                $this->content['error'] = false;
                $this->content['massage'] = "liked_users";
                $this->content['data'] = $liked_users->toArray();
                $status = 200;
            }else{
                $this->content['error'] = false;
                $this->content['massage'] = "no_users.";
                $this->content['data'] ='';
                $status = 200;
            }
            return response()->json($this->content, $status);
        }
    }

}
