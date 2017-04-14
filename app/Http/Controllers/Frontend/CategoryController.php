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

        if(!empty($category)){
            
            // Attach user id to category model
            $category->users()->attach(\Auth::user()->id);

            $category->withCount('users');

            $link=route("frontend.index", ["cat" =>$category->id ]);
            $image_path=asset("img/goole_places_image/".$category->place_image_path);

             $html_result='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 paddingUnset  seperatorGroup">
                <a href="'.$link.'" class="filter-posts" >
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  paddingUnset">
                        <img src="'.$image_path.'" class="imgCircle">
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                        <div class="groupTitle">
                            '.$category->name.'
                        </div> 
                        <div class="groupPopulation text-muted">
                            <i aria-hidden="true" class="fa fa-users"></i> '.$category->users_count.' population
                        </div>
                    </div>
                </a>
            </div>';

            return response()->json(['status' => 200, 'message' => 'success','html_result'=>$html_result]);
        }else{
            return response()->json(['status' => 500, 'message' => 'There was some error while adding new places. Please try again.']);
        }
        
    }
}
