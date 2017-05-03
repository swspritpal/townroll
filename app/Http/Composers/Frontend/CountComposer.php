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
        $total_places_users='';

        $user_post_count = Post::whereUserId(\Auth::id())->count();

        $user_place_count = Category::whereHas('users', function ($query) {
            $query->whereUserId(\Auth::id());
        })->count();


        $total_places_users = \Illuminate\Support\Facades\DB::select("SELECT SUM(`users_count`) as `grand_total_of_users` FROM (select (select count(*) from `users` inner join `category_user` on `users`.`id` = `category_user`.`user_id` where `categories`.`id` = `category_user`.`category_id` and `users`.`deleted_at` is null) as `users_count` from `categories` where exists (select * from `users` inner join `category_user` on `users`.`id` = `category_user`.`user_id` where `categories`.`id` = `category_user`.`category_id` and `user_id` = ? and `users`.`deleted_at` is null)) as Alias_subquery",[\Auth::id()]);

        if(!empty($total_places_users)){
            $total_places_users=$total_places_users['0']->grand_total_of_users;
        }
        $view->with(compact('user_post_count','user_place_count','total_places_users'));
    }
}