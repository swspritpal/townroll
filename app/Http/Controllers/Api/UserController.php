<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Access\User\User;
use Response;

use App\Repositories\Frontend\Access\User\UserRepository;
use App\Helpers\Frontend\Auth\Socialite as SocialiteHelper;
use App\Models\Access\User\SocialLogin;
use App\Repositories\Frontend\Access\Category\CategoryRepository;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $user;

    protected $category;

    /**
     * @var SocialiteHelper
     */
    protected $helper;

    /**
     * SocialLoginController constructor.
     *
     * @param UserRepository  $user
     * @param SocialiteHelper $helper
     */
    public function __construct(UserRepository $user, SocialiteHelper $helper,CategoryRepository $category)
    {
        $this->user = $user;
        $this->helper = $helper;
        $this->category = $category;
        $this->content = array();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($data)
    {
        $user = null;
        $provider=false;

        $provider=$data->provider;

        // Create the user if this is a new social account or find the one that is already there.
        try {
            
            /*
             * If the user does not exist create them
             * The true flag indicate that it is a social account
             * Which triggers the script to use some default values in the create method
             */
            if (! $user) {
                /*// Registration is not enabled
                if (! config('access.users.registration')) {
                    throw new GeneralException(trans('exceptions.frontend.auth.registration_disabled'));
                }*/

                $user = $this->user->create([
                    'name'  => $data->name,
                    'email' => $data->email,
                ], true);
            }

            // See if the user has logged in with this social account before
            if (! $user->hasProvider($provider)) {
                // Gather the provider data for saving and associate it with the user
                $user->providers()->save(new SocialLogin([
                    'provider'    => $provider,
                    'provider_id' => $data->id,
                    'token'       => $data->token,
                    'avatar'      => $data->avatar,
                ]));
            } else {
                // Update the users information, token and avatar can be updated.
                $user->providers()->update([
                    'token'       => $data->token,
                    'avatar'      => $data->avatar,
                ]);
            }

            // Return the user object
            $this->content['error'] = false;
            $this->content['massage'] = "user created successfully.";
            $this->content['user_id'] = $user->id;
            $status = 200;

        } catch (GeneralException $e) {
            $this->content['error'] = true;
            $this->content['massage'] = $e->getMessage();
            $status = 500;
        }

        if (is_null($user) || ! isset($user)) {
            $this->content['error'] = true;
            $this->content['massage'] = "Unknow error";
            $status = 500;
        }
        return response()->json($this->content, $status); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!empty($id)){
            $user_exit=User::whereId($id)->first()->toArray();
            if(!empty($user_exit)){
                $this->content['error'] = false;
                $this->content['massage'] = "User exit";
                $this->content['user'] = $user_exit;
                $status = 200;
            }else{
                $this->content['massage'] = "User does not exit";
                $this->content['error'] = true;
                $status = 500;
            }
        }
        else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }

        return response()->json($this->content, $status); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request)
    {
        $data=(object) $request->all();

        if($request->has('email')){
            $user_exit=User::whereEmail(request('email'))->first();
            if(!empty($user_exit)){
                $this->content['error'] = false;
                $this->content['massage'] = "User exit";
                $this->content['user_id'] = $user_exit->id;
            }else{
                $this->create($data);
            }
            $status = 200;
        }
        else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }

        return response()->json($this->content, $status); 
    }


    /**
     * @param $provider
     *
     * @return mixed
     */
    private function getSocialUser($provider)
    {
        return Socialite::driver($provider)->user();
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     */
    public function signUp(Request $request)
    {
        $input=$request->all();

        if(!empty($input)){
            $user = $this->user->find($input['user_id']);

            $user->username = $input['username'];
            $user->profile_uri = clean_username($input['username']);
            $user->city_id = $input['city'];
            $user->state_id = $input['state'];
            $user->country_id = getCountryId($input['country']);

            
            $defaultCategoryAliasPlaceModel = $this->category->create($input['city'],$input['country']);



            DB::transaction(function () use ($user,$defaultCategoryAliasPlaceModel) {
                if ($user->save()) {
                    /*
                     * Add the default place/category to the new user
                     */                
                    $user->Categories()->attach($defaultCategoryAliasPlaceModel);

                }
            });

            $this->content['error'] = false;
            $this->content['massage'] = "User signup completed";
            $this->content['user'] = $user;
            $status = 200;

        }else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }
        return response()->json($this->content, $status);      

    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function getUserCountData($user_id)
    {   
        $user_post_count=$this->user->get_user_total_post($user_id);
        $user_place_count=$this->user->get_user_total_categories($user_id);
        $total_places_users=$this->user->get_user_total_categories_user($user_id);

        if(!empty($user_id)){
            $this->content['error'] = false;
            $this->content['massage'] = "counter_data.";
            $this->content['data']['user_post_count'] = $user_post_count;
            $this->content['data']['user_place_count'] = $user_place_count;
            $this->content['data']['total_places_users'] = $total_places_users;
            $status = 200;
        }else{
            $this->content['error'] = true;
            $this->content['massage'] = "Invalid Params.";
            $this->content['data'] ='';
            $status = 500;
        }
        return response()->json($this->content, $status);
    }
}


