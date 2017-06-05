<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Access\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Access\User\User;

use App\Repositories\Frontend\Access\Category\CategoryRepository;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;
    protected $category;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user,CategoryRepository $category)
    {
        $this->user = $user;
        $this->category = $category;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {
        $output = $this->user->updateProfile(access()->id(), $request->all());

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            access()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(trans('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(trans('strings.frontend.user.profile_updated'));
    }


    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     */
    public function signUp(Request $request)
    {

        $input=$request->all();


        $user = $this->user->find(access()->id());
        $user->username = $input['username'];
        $user->profile_uri = clean_username($input['username']);
        $user->city_id = $input['city'];
        $user->state_id = $input['state'];
        $user->country_id = getCountryId($input['country']);

        try{
            $defaultCategoryAliasPlaceModel = $this->category->create($input['city'],$input['country']);

            $user_already_attach_with_category=\App\CategoryUser::whereUserId($user->id)->whereCategoryId($defaultCategoryAliasPlaceModel->id)->first();

            DB::transaction(function () use ($user,$defaultCategoryAliasPlaceModel,$user_already_attach_with_category) {
                if ($user->save()) {
                    /*
                     * Add the default place/category to the new user
                     */                
                    if(is_null($user_already_attach_with_category)){
                        $user->Categories()->attach($defaultCategoryAliasPlaceModel);
                    }

                }

            });
        } catch (\App\Exceptions\GeneralException $e){
            if (!App::environment('production', 'staging'))
            {
                var_dump($e->errorInfo );
            }
        }       

        return response()
                    ->json(['status' => 'success','message'=>'You information is saved successfully.']);

       /* else{
                return response()
                ->json(['status' => 'error','message'=>'There was some error while saving your data. Please try again.']);
            }*/

    }

    public function userProfile(Request $request,$username){
        
        $user = User::whereUsername($username)->first();

        if(!empty($user)){
            $sort_by=[];

            $posts = \App\Post::with(
                    [
                        'user',
                        'categories'
                    ]
                )
                ->whereUserId($user->id)
                ->orderBy('created_at', 'desc')
                ->withCount('comments')
                //->toSql();
                ->paginate(env('DEFAULT_HOME_PAGE_POST'));


            $user_post_count=$this->user->get_user_total_post($user->id);
            $user_place_count=$this->user->get_user_total_categories($user->id);
            $total_places_users=$this->user->get_user_total_categories_user($user->id);
            $total_boost_posts=$this->user->get_user_total_boost_posts($user->id);

            return view('frontend.user.profile.view',compact('user','user_post_count','user_place_count','posts','sort_by','total_places_users','total_boost_posts'));
        }else{
            abort(404);
        }
    }

    public function popup(Request $request){

        $user_id=$request->get('id');
        $user = User::whereId($user_id)->first();

        if(!empty($user)){

            $user_post_count=$this->user->get_user_total_post($user->id);
            $user_place_count=$this->user->get_user_total_categories($user->id);
            $total_places_users=$this->user->get_user_total_categories_user($user->id);
            $total_boost_posts=$this->user->get_user_total_boost_posts($user_id);

            $view = \View::make('frontend.includes.popups.user-profile-ajax',compact('user','user_post_count','user_place_count','total_places_users','total_boost_posts'));
            return $view->render();
        }else{
            abort(404);
        }
    }

    public function save_profile_image(Request $request){

        $user_id=\Auth::id();
        $save_path=env('USER_PROFILES_FOLDER');

        $user = User::whereId($user_id)->first();
        $image_source=$request->get('image_src');
        $old_image_source=$request->get('old_image_src');        

        if(!empty($user)){
            $is_image_upload=$this->user->upload_profile_image($image_source,$user,$save_path);

            if(!empty($is_image_upload)){
                // collection full web URL of image need to be insert
                $uploaded_image_name=route('frontend.index').$save_path.$is_image_upload;

                // check Image is unique to insertion
                $is_model_exit=\App\Models\Access\User\UserOldProfiles::whereUserId($user_id)->whereAvatar($uploaded_image_name)->first();
                if(empty($is_model_exit)){
                    $model=new \App\Models\Access\User\UserOldProfiles;
                    $model->user_id=$user_id;
                    $model->avatar=$old_image_source;
                    $is_saved=$model->save();
                }
                

                $user->profile_image=$uploaded_image_name;
                $is_saved=$user->save();

                if(!empty($is_saved)){
                    return response()->json(['status' => 'success', 'msg' => 'Profile has been uploaded successfully.']);
                }else{
                    return response()->json(['status' => 'error', 'msg' => 'There was some error while saving your image.Please try again.']);
                }
            }else{
                return response()->json(['status' => 'error', 'msg' => 'There was some error while uploading your image.Please try again.']);
            }
        }else{
            abort(404);
        }
    }

    
}
