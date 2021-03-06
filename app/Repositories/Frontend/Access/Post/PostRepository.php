<?php

namespace App\Repositories\Frontend\Access\Post;

use App\Configuration;
use App\Post;
use App\Category;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Lufficc\MarkDownParser;
use App\Repositories\Frontend\Repository;
use Illuminate\Support\Facades\DB;

use Approached\LaravelImageOptimizer\ImageOptimizer;

/**
 * design for cache
 *
 *
 * Class PostRepository
 * @package App\Repositories\Frontend\Access
 */
class PostRepository extends Repository
{

    protected $markDownParser;

    static $tag = 'post';

    /**
     * PostRepository constructor.
     * @param MarkDownParser $markDownParser
     */
    public function __construct(MarkDownParser $markDownParser)
    {
        $this->markDownParser = $markDownParser;
    }

    public function model()
    {
        return app(Post::class);
    }

    public function count()
    {
        $count = $this->remember($this->tag() . '.count', function () {
            return $this->model()->withoutGlobalScopes()->count();
        });
        return $count;
    }

    /**
     * @param int $page
     * @return mixed
     */
    public function pagedPostsWithoutGlobalScopes($page = 20)
    {
        $posts = $this->remember('post.WithOutContent.' . $page . '' . request()->get('page', 1), function () use ($page) {
            return Post::withoutGlobalScopes()->orderBy('created_at', 'desc')->select(['id', 'title', 'slug', 'deleted_at', 'published_at', 'status'])->paginate($page);
        });
        return $posts;
    }

    /**
     * @param int $page
     * @return mixed
     */
    public function pagedPosts($page = 7)
    {
        $posts = $this->remember('post.page.' . $page . '' . request()->get('page', 1), function () use ($page) {
            return Post::select(Post::selectArrayWithOutContent)->with(['tags', 'category'])->withCount('comments')->orderBy('created_at', 'desc')->paginate($page);
        });
        return $posts;
    }

    /**
     * @param $slug string
     * @return Post
     */
    public function get($slug)
    {
        $post = $this->remember('post.one.' . $slug, function () use ($slug) {
            return Post::where('slug', $slug)->with(['tags', 'category', 'configuration'])->withCount('comments')->firstOrFail();
        });
        return $post;
    }

    public function hotPosts($count = 5)
    {
        $posts = $this->remember('post.achieve.' . $count, function () use ($count) {
            return Post::select([
                'title',
                'slug',
                'view_count',
            ])->withCount('comments')->orderBy('view_count', 'desc')->limit($count)->get();
        });
        return $posts;
    }

    public function achieve()
    {
        $posts = $this->remember('post.achieve', function () {
            return Post::select([
                'id',
                'title',
                'slug',
                'created_at',
            ])->orderBy('created_at', 'desc')->get();
        });
        return $posts;
    }

    public function recommendedPosts(Post $post)
    {
        $recommendedPosts = $this->remember('post.recommend.' . $post->slug, function () use ($post) {
            $category = $post->category;
            $tags = [];
            foreach ($post->tags as $tag) {
                array_push($tags, $tag->name);
            }
            $recommendedPosts = Post
                ::where('category_id', $category->id)
                ->Where('id', '<>', $post->id)
                ->orderBy('view_count', 'desc')
                ->select(Post::selectArrayWithOutContent)
                ->limit(5)
                ->get();
            return $recommendedPosts;
        });
        return $recommendedPosts;
    }

    public function postCount()
    {
        $count = $this->remember('post-count', function () {
            return Post::count();
        });
        return $count;
    }

    public function getWithoutContent($post_id)
    {
        $post = $this->remember('post.one.wc.' . $post_id, function () use ($post_id) {
            return Post::where('id', $post_id)->select(Post::selectArrayWithOutContent)->first();
        });
        if (!$post)
            abort(404);
        return $post;
    }

    /**
     * @param Request $request
     * @return mixed
     */

    public function create(Request $request,ImageOptimizer $imageOptimizer,$call_from_api=false)
    {
        $this->clearAllCache();

        $image_name=$success='';

        $ids =$categories= [];
        /*$tags = $request['tags'];
        if (!empty($tags)) {
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                array_push($ids, $tag->id);
            }
        }
        $status = $request->get('status', 0);
        if ($status == 1) {
            $request['published_at'] = Carbon::now();
        }*/

        if($call_from_api == false){
            $upload_dir=public_path().env('POST_IMAGES_FOLDER');
            if($request->has('file')){
                 $img=$request->input('file');

                list($type, $data) = explode(';', $img);
                list(, $data)      = explode(',', $data);
                list($type_content, $extension) = explode('/', $type);
                $img = str_replace(' ', '+', $data);
                $data = base64_decode($img);        

                $image_name=mt_rand().'.'.$extension;      
                $file = $upload_dir.$image_name;
                $success = file_put_contents($file, $data);

                if(env('USE_OPTIMIZER') && (\File::exists($file))){
                   // optimize
                    $imageOptimizer->optimizeImage($file);
                    // override the previous image with optimized once
                    $is_optimized = file_put_contents($file, \File::get($file));
                    if($is_optimized == false || empty($is_optimized)){
                        \Log::warning('Post Image does not optimized. Named : '.$image_name);
                    }
                }
                if(empty($success)){
                    return response()
                            ->json(['status' => 'error','message'=>'Image not uploaded. Please try again.']);
                }
            }
        }else{
            $image_name=$request->get('image_name');
            $user_modal=\App\Models\Access\User\User::whereId($request->get('user_id'))->first();
        }
        

        if($call_from_api == false){
            $post = auth()->user()->posts()->create(
                [
                    'html_content' => $this->markDownParser->parse($request->get('post_content'), false),
                    'content' => $request->get('post_content'),
                    'image_path' => $image_name,
                    'status' => '1',
                ]
                
            );
        }else{            
            $post = $user_modal->posts()->create(
                [
                    'html_content' => $this->markDownParser->parse($request->get('post_content'), false),
                    'content' => $request->get('post_content'),
                    'image_path' => $image_name,
                    'status' => '1',
                ]
                
            );
        }

    $categories=$request->get('post_categories');

    // If option containing All then attach post to all categories
    if( (is_array($categories) && in_array('all', $categories)) || (strpos($categories,'all') !== false) ) {
        if($call_from_api == false){
            $categories=auth()->user()->categories()->pluck('categories.id')->toArray();
        }else{
            $categories=$user_modal->categories()->pluck('categories.id')->toArray();
        }
    }else{
        $categories=explode(',', $categories);
    }
    

    DB::transaction(function () use ($post,$categories) {
        if ($post->save()) {
            /*
             * Add the categories to the new post
             */             
            $post->categories()->attach($categories);
        }

    });    
    return $post;

        //$post->tags()->sync($ids);
        //$post->saveConfig($request->all());
        
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return bool|int
     */

    public function update(Request $request, Post $post)
    {
        $this->clearAllCache();

        $ids = [];
        $tags = $request['tags'];
        if (!empty($tags)) {
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                array_push($ids, $tag->id);
            }
        }
        $post->tags()->sync($ids);

        $status = $request->get('status', 0);
        if ($status == 1) {
            $request['published_at'] = Carbon::now();
        }

        $post->saveConfig($request->all());

        return $post->update(
            array_merge(
                $request->except(['_token', 'description']),
                [
                    'html_content' => $this->markDownParser->parse($request->get('content'), false),
                    'description' => $this->markDownParser->parse($request->get('description'), false),
                ]
            ));
    }

    public function delete(Post $post, $force = false)
    {
        $this->clearCache();        
        if ($force)
            return $post->forceDelete();
        return $post->delete();
    }

    public function tag()
    {
        return PostRepository::$tag;
    }
}