<?php
namespace App\Http\Composers\Frontend;

use App\Repositories\CategoryRepository;
use Illuminate\View\View;
use App\Category;

class CategoriesComposer
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
        $categories = Category::whereHas('users', function ($query) {
                $query->where('user_id', '=', \Auth::user()->id);
            })
            ->withCount('users')->take(env('DEFAULT_HOME_PAGE_PLACES'))->get();

        $view->with('categories', $categories);
    }
}