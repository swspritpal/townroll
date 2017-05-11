<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Slap;
use Illuminate\Support\Facades\Auth;

class SlapController extends Controller
{


    public function slapPost(Request $request,$id)
    {
        if(!empty(Auth::id())){
            $user_id=Auth::id(); 
        }else{
            $user_id=$request->get('user_id');
        }

        $this->handleSlap('App\Post', $id,$user_id);
        return redirect()->back();
    }

    public function handleSlap($type, $id,$user_id)
    {
        $existing_slap = Slap::withTrashed()->whereSlapableType($type)->whereSlapableId($id)->whereUserId($user_id)->first();

        if (is_null($existing_slap)) {
            $slap_model=Slap::create([
                'user_id'       => $user_id,
                'slapable_id'   => $id,
                'slapable_type' => $type,
            ]);

            $postData=\App\Post::whereId($id)->first();
            $notifyTo=$postData->user_id;

            // when some other user likes post then notify to Auther 
            if($notifyTo != $user_id){
                $user_notification=\FeedManager::getUserFeed($user_id);

                // Push notification for Stream about the like action
                $data = [
                    "actor"=>"\App\Models\Access\User\User:".$user_id,
                    "verb"=>"slap",
                    "object"=>"\App\Post:".$id,
                    "foreign_id"=>"\App\Slap:".$slap_model->id,
                    "is_read" => false,
                    "is_seen" => false,
                    'to' => ['notification:'.$notifyTo],
                ];
                $user_notification->addActivity($data);
            }
        } else {
            if (is_null($existing_slap->deleted_at)) {
                $existing_slap->delete();
            } else {
                $existing_slap->restore();
            }
        }
    }

    /**
     * Show liked post users list
     *
     * @param  Request $request
     * @return Response
     */
    public function slappedUsersList(Request $request,$post_id)
    {

        $html_result='';

        $slapped_users=Slap::where('slapable_id', $post_id)
                ->with(['users'=>function($user_query){
                	$user_query->select('users.id','username','profile_uri');
                }])
                ->orderBy('created_at','desc')
                ->selectRaw('`slaps`.*,(select count(*) from `users` inner join `slaps` on `users`.`id` = `slaps`.`user_id` where  `slaps`.`slapable_id` = '.$post_id.' and `users`.`deleted_at` is null) as `users_count`')
                ->paginate(env('DEFAULT_HOME_PAGE_VIEWED_OR_LIKED_USERS_LIMIT'));
                //->toSql();

        if(!empty($slapped_users)){
            $view = \View::make('frontend.includes.posts.viewed_or_liked_list',['viewed_or_liked_users'=>$slapped_users]);
            $html_result = $view->render();
        }else{
            $html_result = 'users not found ';
        }

        return response()->json(['status' => 200, 'message' => 'success','html_result'=>$html_result]);
    }

}
