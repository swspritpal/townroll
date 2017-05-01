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

class PostController extends Controller
{

    public function __construct()
    {
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
        //
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

    public function login(Request $request)
    {
        $data=(object) $request->all();

        if($request->has('email')){
            $user_exit=User::whereEmail(request('email'))->first();
            if(!empty($user_exit)){
                $this->content['error'] = false;
                $this->content['massage'] = "User exit";
                $this->content['user_id'] = $user_exit->id;
            }else{
                $this->create($data);
            }
            $status = 200;
        }
        else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }

        return response()->json($this->content, $status); 
    }


    /**
     * @param $provider
     *
     * @return mixed
     */
    private function getSocialUser($provider)
    {
        return Socialite::driver($provider)->user();
    }

    public function check(){        
        $this->content['massage'] = "Invalid params";
        $this->content['error'] = true;
        $status = 500;
        return response()->json($this->content, $status);

    }
}


