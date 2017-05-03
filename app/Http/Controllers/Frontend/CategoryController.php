<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Repositories\Frontend\Access\Category\CategoryRepository;
use App\Http\Requests;
use Illuminate\Http\Request;
use XblogConfig;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    protected $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function index()
    {
        return view('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param $name
     * @return \Illuminate\Http\Response
     * @internal param Category $category
     * @internal param int $id
     */

    public function show($name)
    {
        $category = $this->categoryRepository->get($name);
        $page_size = $page_size = XblogConfig::getValue('page_size', 7);
        $posts = $this->categoryRepository->pagedPostsByCategory($category, $page_size);
        return view('category.show', compact('posts', 'name'));
    }


    public function store(Request $request)
    {
        $category = $this->categoryRepository->add_new($request);
        $slick_last_index=$request->get('slick_last_index');

        if(!empty($category)){
            
            // Attach user id to category model
            $category->users()->attach(\Auth::user()->id);
            $category->withCount('users');

            $user_category=Category::whereId($category->id)->withCount('users')->first();

            $view = \View::make('frontend.includes.categories.horizontal',['user_category'=>$user_category]);
            $html_result['horizontal'] = $view->render();

            $view = \View::make('frontend.includes.categories.vertical',['user_category'=>$user_category,'slick_last_index'=>$slick_last_index]);
            $html_result['vertical'] = $view->render();

            return response()->json(['status' => 200, 'message' => 'success','html_result'=>$html_result]);
        }else{
            return response()->json(['status' => 500, 'message' => 'There was some error while adding new places. Please try again.']);
        }
        
    }
}
