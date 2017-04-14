<?php
namespace App\Http\Composers\Frontend;

use Illuminate\View\View;
use App\Models\Access\User\User;
use App\Post;
use App\Category;

class CountComposer
{

   /* protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }*/

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $user_post_count = Post::where('user_id','=',\Auth::user()->id)->count();

        $user_place_count = Category::whereHas('users', function ($query) {
            $query->where('user_id', '=', \Auth::user()->id);
        })->count();

        /*$total_places_users_excluded_login = User::with('category', function ($query) {
            $query->where('user_id', '=', \Auth::user()->id);
        })->count();*/

        $view->with(compact('user_post_count','user_place_count'));
    }
}