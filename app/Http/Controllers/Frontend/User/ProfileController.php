<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Access\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        
        $defaultCategoryAliasPlaceModel = $this->category->create($input['city'],$input['country']);



        DB::transaction(function () use ($user,$defaultCategoryAliasPlaceModel) {
            if ($user->save()) {
                /*
                 * Add the default place/category to the new user
                 */                
                $user->Categories()->attach($defaultCategoryAliasPlaceModel);

            }

        });

        return response()
                    ->json(['status' => 'success','message'=>'You information is saved successfully.']);

       /* else{
                return response()
                ->json(['status' => 'error','message'=>'There was some error while saving your data. Please try again.']);
            }*/

    }
}
