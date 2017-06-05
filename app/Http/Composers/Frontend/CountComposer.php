<?php
namespace App\Http\Composers\Frontend;

use Illuminate\View\View;
use App\Models\Access\User\User;
use App\Post;
use App\Category;

use App\Repositories\Frontend\Access\User\UserRepository;

class CountComposer
{

   /**
     * @var UserRepository
     */
    protected $user;

    /**
     * CountComposer constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {

        $user_id=\Auth::id();
        
        $user_post_count=$this->user->get_user_total_post($user_id);
        $user_place_count=$this->user->get_user_total_categories($user_id);
        $total_places_users=$this->user->get_user_total_categories_user($user_id);
        $total_boost_posts=$this->user->get_user_total_boost_posts($user_id);

        $view->with(compact('user_post_count','user_place_count','total_places_users','total_boost_posts'));
    }
}