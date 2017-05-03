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


            $user_post_count = \App\Post::whereUserId($user->id)->count();

            $user_place_count = \App\Category::whereHas('users', function ($query) use ($user){
                $query->whereUserId($user->id);
            })->count();

            $total_places_users = \Illuminate\Support\Facades\DB::select("SELECT SUM(`users_count`) as `grand_total_of_users` FROM (select (select count(*) from `users` inner join `category_user` on `users`.`id` = `category_user`.`user_id` where `categories`.`id` = `category_user`.`category_id` and `users`.`deleted_at` is null) as `users_count` from `categories` where exists (select * from `users` inner join `category_user` on `users`.`id` = `category_user`.`user_id` where `categories`.`id` = `category_user`.`category_id` and `user_id` = ? and `users`.`deleted_at` is null)) as Alias_subquery",[$user->id]);

            if(!empty($total_places_users)){
                $total_places_users=$total_places_users['0']->grand_total_of_users;
            }

            return view('frontend.user.profile.view',compact('user','user_post_count','user_place_count','posts','sort_by','total_places_users'));
        }else{
            abort(404);
        }
    }

    public function popup(Request $request){

        $user_id=$request->get('id');
        $user = User::whereId($user_id)->first();

        if(!empty($user)){
            $user_post_count = \App\Post::whereUserId($user->id)->count();

            $user_place_count = \App\Category::whereHas('users', function ($query) use ($user){
                $query->whereUserId($user->id);
            })->count();

            $view = \View::make('frontend.includes.popups.user-profile-ajax',compact('user','user_post_count','user_place_count'));
            return $view->render();
        }else{
            abort(404);
        }
    }

    
}
