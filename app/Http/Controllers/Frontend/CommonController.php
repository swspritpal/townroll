<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Country\Country;
Use App\Models\Country\State;
Use App\Models\Country\City;
use App\Models\Access\User\User;

/**
 * Class FrontendController.

    For various methods
 */
class CommonController extends Controller
{
    /**
     * @return \Illuminate\Response\Json
     */
    public function getStates(Request $request,$country_sortname)
    {   
        $country_id=getCountryId($country_sortname);

        $states = getStateList($country_id);

        if(!empty($states)){
            return response()
            ->json(['result' =>$states, 'status' => 'success','message'=>'success']);
            
        }else{
            return response()
            ->json(['status' => 'error','message'=>'empty states']);
        }
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function getCities(Request $request,$state_id)
    {   

        $cities = getCityList($state_id);

        if(!empty($cities)){
            return response()
            ->json(['result' =>$cities, 'status' => 'success','message'=>'success']);
            
        }else{
            return response()
            ->json(['status' => 'error','message'=>'empty cities']);
        }
    }


    /**
     * @return \Illuminate\Response\Json
     */
    public function unique_username(Request $request)
    {   
        $username=$request->input('username');
        $response = is_username_unique($username);

        if($response){
            return response()
            ->json('Username already taken.');
        }else{
            return response()
            ->json('true');
        }
    }


    /**
     * @return \Illuminate\Response\Json
     */
    public function findNearPlaces(Request $request)
    {   

        $html_result='';
        
        $radius=env('DEFAULT_PALCES_SEARCH_RADIUS');
        $response=nearbysearch(get_latitude().','.get_longitude(),$radius);

        $image_base_path=env('PLACE_IMAGES_FOLDER');

        if(!empty($response) && $response['status'] == 'OK'){
            foreach($response['results'] as $place){

                if(is_user_already_joined_place($place['place_id'])){
                    continue;
                }

                if(isset($place['photos'])){

                    // Check place image is already exit on server
                    if(file_exists( public_path() . $image_base_path . $place['place_id'].'.png')) {
                        $place_photo=$place['place_id'].'.png';
                    }else{
                        $place_photo=placephoto($place['photos']['0']['photo_reference'],$place['place_id']);    
                    }                    
                }else{
                    $place_photo='na.png';
                }                

                $html_result .='<div class="row">
                  <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
                      <div class="imgAbt">
                          <img class="img-responsive" src="'.asset($image_base_path.$place_photo).'" />
                      </div>
                  </div>
                  <div class="col-lg-8 col-md-6 col-sm-6 col-xs-6">
                        <h4 class="margin-top-unset">'.$place['name'].'</h4>
                        <p class="make-line-height">'.$place['vicinity'].'</p>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <form class="add-new-place-form">
                        <input type="hidden" name="name" value="'.$place['name'].'" >
                        <input type="hidden" name="vicinity" value="'.$place['vicinity'].'" >
                        <input type="hidden" name="place_id" value="'.$place['place_id'].'" >
                        <input type="hidden" name="latitude" value="'.$place['geometry']['location']['lat'].'" >
                        <input type="hidden" name="longitude" value="'.$place['geometry']['location']['lng'].'" >                        
                        <input type="hidden" name="place_image_path" value="'.$place_photo.'" >
                        <button class="join-new-place btn btn-primary">Join</button>'
                            .(!empty(get_place_users_count($place['place_id'])) ? '
                                <div><i class="fa fa-users" aria-hidden="true"></i>
                                '.get_place_users_count($place['place_id']).'</div>': '').                       
                    '</form>
                  </div>
                </div>';
            }
        }else{
            $html_result='<div class="row">
                <h4>no result found</h4>
                </div>';
        }

        return response()->json(['status' => 200, 'message' => 'success','html_result'=>$html_result]);
    }


    /**
     * @return \Illuminate\Response\Json
     */
    public function markNotificationRead()
    {   
        $notification_feed = \FeedManager::getNotificationFeed(\Auth::id());
        $options = array('mark_seen' => true);
        $results = $notification_feed->getActivities(0,100,$options);

        if(!empty($results)){
            return response()
            ->json('true');
        }else{
            return response()
            ->json('some error while marking as seen.');
        }
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function suggest_user_list(Request $request)
    {   
        $html_result='';

        $search_char=$request->get('search');
        $search_char=str_replace('@','',$search_char);

        if(!empty($search_char)){
            $users=\App\Models\Access\User\User::like('username', $search_char)->orderBy('username','DESC')->limit(env('DEFAULT_USER_SUGGESTION_LIMIT'))->get();
            
            if(!empty($users)){
                $html_result.='<div class="suggest-user-output">';
                    foreach ($users as $user) {
                        $html_result.='
                            <div class="row suggest-user-click '.$user->id.'">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
                                    <img src="'.$user->picture.'" alt="" class="profile-photo-sm">
                                </div>
                               <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 ">
                                  <span class="suggest-selected-username">'.$user->username.'</span>
                                </div>
                            </div>';
                    }
                $html_result.='</div>';
                return $html_result;
            }
        }
        
    }

}
