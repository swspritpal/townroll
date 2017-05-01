<?php
namespace App\Repositories\Frontend\Access\Category;

use App\Category;
use App\Post;
use Illuminate\Http\Request;
use App\Repositories\Frontend\Repository;
use App\Exceptions\GeneralException;

/**
 * Class CategoryRepository
 * @package App\Http\Repository
 */
class CategoryRepository extends Repository
{
    static $tag = 'category';

    public function model()
    {
        return app(Category::class);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $categories = $this->remember('category.all', function () {
            return Category::withCount('posts')->get();
        });
        return $categories;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        $category = $this->remember('category.one.' . $name, function () use ($name) {
            return Category::where('name', $name)->first();
        });
        if (!$category)
            abort(404);
        return $category;
    }

    public function pagedPostsByCategory(Category $category, $page = 7)
    {
        $posts = $this->remember('category.posts.' . $category->name . $page . request()->get('page', 1), function () use ($category, $page) {
            return $category->posts()->select(Post::selectArrayWithOutContent)->with(['tags', 'category'])->withCount('comments')->orderBy('created_at', 'desc')->paginate($page);
        });
        return $posts;
    }

    /**
     * @param Request $request
     * @return Category
     */
    public function create($city_id,$country_sortname,$input=null)
    {
        $this->clearCache();

        $alreadyHavePlace = Category::where('city_id', $city_id)->first();

        if(!empty($alreadyHavePlace)){
            return $alreadyHavePlace;
        }else{

            $place_id=$photo_reference=$place_photo_name='';

            //If request from App then GEO details is already there in request.
            if(!empty($input)){
                $place_id=$input['place_id'];
                $latitude=$input['latitude'];
                $longitude=$input['longitude'];
                
                // need to upload the photo to server
                $place_photo_name=$input['place_photo_name'];
            }

            if(empty($place_id)){
                $latitude=get_latitude();
                $longitude=get_longitude();

                $get_place_details=geocoding($city_id,$latitude.','.$longitude,$country_sortname);

                if(!empty($get_place_details['status'] == "OK")){
                    $place_id=$get_place_details['results'][0]['place_id'];

                    if(!empty($place_id)){
                        $place_photo_reference=placedetails($place_id);

                        if(!empty($place_photo_reference['status'] == "OK")){
                            $photo_reference=$place_photo_reference['result']['photos'][0]['photo_reference'];

                            $place_photo_response=placephoto($photo_reference,$place_id);
                            if($place_photo_response != "error"){
                                $place_photo_name=$place_photo_response;
                            }                      

                        }else{
                            throw new GeneralException("Place details API return error.");
                        }
                    }
                    else{
                        throw new GeneralException("Geo place_id empty.");
                    }

                }else{
                    throw new GeneralException("Geo coding return invalid request !");
                }
            }
            

            if(!empty($place_id)){
                try {
                    $category = Category::create(['name' => get_city_name($city_id),'geo_place_id'=>$place_id,'latitude'=>$latitude,'longitude'=>$longitude,'place_image_path'=>$place_photo_name,'is_parent'=>'yes','city_id'=>$city_id ]);
                    return $category->id;
                }
                catch (Illuminate\Database\QueryException $e){
                    if (!App::environment('production', 'staging'))
                    {
                        var_dump($e->errorInfo );
                    }
                }
            }else{
                throw new GeneralException("Geo place_id empty.");
            }
    
        }
        
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return bool|int
     */
    public function update(Request $request, Category $category)
    {
        $this->clearCache();
        return $category->update($request->all());
    }


    public function is_unique($place_id=null)
    {
        return Category::where('geo_place_id', $place_id)->first();
    }

    

    /**
     * @return mixed
     */
    public function add_new(Request $request)
    {
        $place_id=$request->get('place_id');

        $category=$this->is_unique($place_id);

        if(empty($category)){
            $category = Category::create(['name' => $request->get('name'),'vicinity' => $request->get('vicinity'),'geo_place_id'=>$place_id,'latitude'=>$request->get('latitude'),'longitude'=>$request->get('longitude'),'place_image_path'=>$request->get('place_image_path')]);
            return $category;
        }else{
            return $category;
        }
       
    }

    public function tag()
    {
        return CategoryRepository::$tag;
    }
}