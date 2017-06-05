<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Models\Access\User\User;
use App\Post;
use App\Category;
use Carbon\Carbon;
use App\Repositories\Frontend\Access\Post\PostRepository;


class PostController extends Controller
{

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->content = array();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id=null,$sort_by=null)
    {
        if(!empty($user_id)){

            $posts = Post::with(
                    [
                        'user',
                        'categories',
                        'comments'=>function($comment_query){
                            return $comment_query->limit(env('DEFAULT_HOME_PAGE_POST_COMMENTS_FOR_APP'));
                        },
                    ]
                )
                ->orderBy('created_at', 'desc')
                ->withCount('comments')
                ->withCount('likes')
                ->withCount('views')
                ->withCount('slaps')
                ->whereIn('id', function($category_post_query) use($sort_by,$user_id){
                    $category_post_query
                        ->select('post_id')
                        ->from(with(new \App\CategoryPost)->getTable())
                        ->whereIn('category_id',function($category_query) use($sort_by,$user_id){
                                $category_query
                                    ->select(['category_id'])
                                    ->from(with(new \App\CategoryUser)->getTable())
                                    ->where('user_id', '=', $user_id);

                                if(!empty($sort_by)){
                                    $category_query->where('category_id', '=', $sort_by['cat']);
                                }
                                $category_query->get()
                                ->toArray();
                        })
                        ->get()
                        ->toArray();

                })
                ->paginate(env('DEFAULT_HOME_PAGE_POST_FOR_APP'))
               ->toArray();

            if(!empty($posts)){
                $this->content['error'] = false;
                $this->content['massage'] = "User have posts.";
                $this->content['data'] = $posts;
                $status = 200;
            }else{
                $this->content['error'] = false;
                $this->content['massage'] = "Posts not found yet.";
                $this->content['data'] ='';
                $status = 200;
            }
        }else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }
        
        return response()->json($this->content, $status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $imageOptimizer=new \Approached\LaravelImageOptimizer\ImageOptimizer;

        $post= $this->postRepository->create($request,$imageOptimizer,$call_from_api=true);

        if(!empty($post)){
            $this->content['massage'] = "post_saved_successfully";
            $this->content['error'] = false;
            $status = 200;
        }else{
            $this->content['massage'] = "post_not_saved_error_unknown";
            $this->content['error'] = true;
            $status = 500;
        }

        return response()->json($this->content, $status); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!empty($id)){
            $user_exit=User::whereId($id)->first()->toArray();
            if(!empty($user_exit)){
                $this->content['error'] = false;
                $this->content['massage'] = "User exit";
                $this->content['user'] = $user_exit;
                $status = 200;
            }else{
                $this->content['massage'] = "User does not exit";
                $this->content['error'] = true;
                $status = 500;
            }
        }
        else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }

        return response()->json($this->content, $status); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function is_post_liked_by_user($post_id=null,$user_id=null)
    {        
        if(!empty($post_id) && !empty($user_id)){
            $result=is_post_liked_by_user($post_id,$user_id);

            if($result){
                $this->content['error'] = false;
                $this->content['massage'] = "user_liked_this_post";
                $this->content['result'] = $result;
                $status = 200;
            }else{
                $this->content['massage'] = "user_does_not_liked_this_post";
                $this->content['error'] = false;
                $this->content['result'] = $result;
                $status = 200;
            }
        }else{
            $this->content['massage'] = "invalid_params";
            $this->content['error'] = true;
            $status = 500;
        }
        return response()->json($this->content, $status);        
    }

    public function is_post_slapped_by_user($post_id=null,$user_id=null)
    {        
        if(!empty($post_id) && !empty($user_id)){
            $result=is_post_slapped_by_user($post_id,$user_id);

            if($result){
                $this->content['error'] = false;
                $this->content['massage'] = "user_slaped_this_post";
                $this->content['result'] = $result;
                $status = 200;
            }else{
                $this->content['massage'] = "user_does_not_slaped_this_post";
                $this->content['error'] = false;
                $this->content['result'] = $result;
                $status = 200;
            }
        }else{
            $this->content['massage'] = "invalid_params";
            $this->content['error'] = true;
            $status = 500;
        }
        return response()->json($this->content, $status);        
    }


    
}


