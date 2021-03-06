<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\Access\User\SocialLogin;
use App\Events\Frontend\Auth\UserConfirmed;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @var RoleRepository
     */
    protected $role;

    /**
     * @param RoleRepository $role
     */
    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function findByEmail($email)
    {
        return $this->query()->where('email', $email)->first();
    }

    /**
     * @param $token
     *
     * @throws GeneralException
     *
     * @return mixed
     */
    public function findByToken($token)
    {
        return $this->query()->where('confirmation_code', $token)->first();
    }

    /**
     * @param $token
     *
     * @throws GeneralException
     *
     * @return mixed
     */
    public function getEmailForPasswordToken($token)
    {
        $rows = DB::table(config('auth.passwords.users.table'))->get();

        foreach ($rows as $row) {
            if (password_verify($token, $row->token)) {
                return $row->email;
            }
        }

        throw new GeneralException(trans('auth.unknown'));
    }

    /**
     * @param array $data
     * @param bool  $provider
     *
     * @return static
     */
    public function create(array $data, $provider = false)
    {
        $user = self::MODEL;
        $user = new $user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->status = 1;
        $user->password = $provider ? null : bcrypt($data['password']);
        $user->confirmed = $provider ? 1 : (config('access.users.confirm_email') ? 0 : 1);

        DB::transaction(function () use ($user) {
            if ($user->save()) {
                /*
                 * Add the default site role to the new user
                 */
                $user->attachRole($this->role->getDefaultUserRole());
            }
        });

        /*
         * If users have to confirm their email and this is not a social account,
         * send the confirmation email
         *
         * If this is a social account they are confirmed through the social provider by default
         */
        if (config('access.users.confirm_email') && $provider === false) {
            $user->notify(new UserNeedsConfirmation($user->confirmation_code));
        }

        /*
         * Return the user object
         */
        return $user;
    }

    /**
     * @param $data
     * @param $provider
     *
     * @return UserRepository|bool
     * @throws GeneralException
     */
    public function findOrCreateSocial($data, $provider)
    {
        // User email may not provided.
        $user_email = $data->email ?: "{$data->id}@{$provider}.com";

        // Check to see if there is a user with this email first.
        $user = $this->findByEmail($user_email);

        /*
         * If the user does not exist create them
         * The true flag indicate that it is a social account
         * Which triggers the script to use some default values in the create method
         */
        if (! $user) {
            // Registration is not enabled
            if (! config('access.users.registration')) {
                throw new GeneralException(trans('exceptions.frontend.auth.registration_disabled'));
            }

            $user = $this->create([
                'name'  => $data->name,
                'email' => $user_email,
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
        return $user;
    }

    /**
     * @param $token
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function confirmAccount($token)
    {
        $user = $this->findByToken($token);

        if ($user->confirmed == 1) {
            throw new GeneralException(trans('exceptions.frontend.auth.confirmation.already_confirmed'));
        }

        if ($user->confirmation_code == $token) {
            $user->confirmed = 1;

            event(new UserConfirmed($user));

            return $user->save();
        }

        throw new GeneralException(trans('exceptions.frontend.auth.confirmation.mismatch'));
    }

    /**
     * @param $id
     * @param $input
     *
     * @throws GeneralException
     *
     * @return mixed
     */
    public function updateProfile($id, $input)
    {
        $user = $this->find($id);
        $user->name = $input['name'];

        if ($user->canChangeEmail()) {
            //Address is not current address
            if ($user->email != $input['email']) {
                //Emails have to be unique
                if ($this->findByEmail($input['email'])) {
                    throw new GeneralException(trans('exceptions.frontend.auth.email_taken'));
                }

                // Force the user to re-verify his email address
                $user->confirmation_code = md5(uniqid(mt_rand(), true));
                $user->confirmed = 0;
                $user->email = $input['email'];
                $updated = $user->save();

                // Send the new confirmation e-mail
                $user->notify(new UserNeedsConfirmation($user->confirmation_code));

                return [
                    'success' => $updated,
                    'email_changed' => true,
                ];
            }
        }

        return $user->save();
    }

    /**
     * @param $input
     *
     * @throws GeneralException
     *
     * @return mixed
     */
    public function changePassword($input)
    {
        $user = $this->find(access()->id());

        if (Hash::check($input['old_password'], $user->password)) {
            $user->password = bcrypt($input['password']);

            return $user->save();
        }

        throw new GeneralException(trans('exceptions.frontend.auth.password.change_mismatch'));
    }


    /**
     * 
     * @throws GeneralException
     *
     * @return mixed
     */
    public function get_user_total_post($user_id)
    {
        $user_post_count = \App\Post::whereUserId($user_id)->count();
        return $user_post_count;
    }

    public function get_user_total_categories($user_id)
    {
        $user_place_count = \App\Category::whereHas('users', function ($query) use ($user_id){
            $query->whereUserId($user_id);
        })->count();
        return $user_place_count;
    }
    public function get_user_total_categories_user($user_id)
    {
        $total_places_users=null;
        $total_places_users = \Illuminate\Support\Facades\DB::select("SELECT SUM(`users_count`) as `grand_total_of_users` FROM (select (select count(*) from `users` inner join `category_user` on `users`.`id` = `category_user`.`user_id` where `categories`.`id` = `category_user`.`category_id` and `users`.`deleted_at` is null) as `users_count` from `categories` where exists (select * from `users` inner join `category_user` on `users`.`id` = `category_user`.`user_id` where `categories`.`id` = `category_user`.`category_id` and `user_id` = ? and `users`.`deleted_at` is null)) as Alias_subquery",[$user_id]);

        if(!empty($total_places_users)){
            $total_places_users=$total_places_users['0']->grand_total_of_users;
        }
        return $total_places_users;
    }


    public function get_user_total_boost_posts($user_id)
    {
        $boost_post_counter = \App\BoostPost::whereUserId($user_id)->count();
        return $boost_post_counter;
    }

    public function upload_profile_image($image_source,$user,$save_path)
    {
       $upload_dir=public_path().$save_path;

        list($type, $data) = explode(';', $image_source);
        list(, $data)      = explode(',', $data);
        list($type_content, $extension) = explode('/', $type);
        $image_source = str_replace(' ', '+', $data);
        $data = base64_decode($image_source);        

        $image_name=$user-> username.'-'.mt_rand().'.'.$extension;
        $file = $upload_dir.$image_name;

        $is_uploaded = file_put_contents($file, $data);
        if(!empty($is_uploaded)){

            if(env('USE_OPTIMIZER')){
                $imageOptimizer=new \Approached\LaravelImageOptimizer\ImageOptimizer;
               // optimize
                $imageOptimizer->optimizeImage($file);
                // override the previous image with optimized once
                $is_optimized = file_put_contents($file, \File::get($file));
                if($is_optimized == false || empty($is_optimized)){
                    \Log::warning('User profile Image does not optimized. Named : '.$image_name);
                }
            }
            return $image_name;
        }else{
            return false;
        }
    }

    
}
 
