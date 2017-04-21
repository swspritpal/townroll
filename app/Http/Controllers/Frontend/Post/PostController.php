<?php

namespace App\Http\Controllers\Frontend\Post;

use App\Repositories\Frontend\Access\Category\CategoryRepository;
use App\Repositories\Frontend\Access\Comment\CommentRepository;
use App\Repositories\Frontend\Access\Post\PostRepository;
use App\Repositories\Frontend\Access\Tag\TagRepository;
use App\Http\Requests;
use App\Notifications\UserRegistered;
use App\Post;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use League\HTMLToMarkdown\HtmlConverter;
use Lufficc\Post\PostHelper;
use App\Http\Controllers\Controller;
use Approached\LaravelImageOptimizer\ImageOptimizer;
use App\View;
use App\Like;



class PostController extends Controller
{
    use PostHelper;
    protected $postRepository;
    protected $commentRepository;

    /**
     * PostController constructor.
     * @param PostRepository $postRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(PostRepository $postRepository, CommentRepository $commentRepository)
    {

        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }


    public function index()
    {
        
        //$page_size = XblogConfig::getValue('page_size', 7);
        $page_size=7;
        $posts = $this->postRepository->pagedPosts($page_size);
        return view('frontend.post.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = $this->postRepository->get($slug);
        //$recommendedPosts = $this->postRepository->recommendedPosts($post);
        $comments = $this->commentRepository->getByCommentable('App\Post', $post->id);
        $this->onPostShowing($post);
        return view('frontend.post.show', compact('post', 'comments'));
    }

    public function store(Request $request,ImageOptimizer $imageOptimizer)
    {
        
        $post= $this->postRepository->create($request,$imageOptimizer);

        if(!empty($post)){
            $post=Post::with(
                    [
                        'user',
                        'categories'
                    ]
                )
                ->where('status','=','1')
                ->where('id','=',$post->id)
                ->withCount('comments')
                ->first();
            //dd($post);

            $view = \View::make('frontend.includes.posts.single',compact('post'));
            $html_result = $view->render();
             
            return response()
                ->json(['status' => 'success','message'=>'Your post published successfully.','html_result'=>$html_result]);
        }else{
            return response()
                ->json(['status' => 'error','message'=>'There was some error while saving record. Please try again.']);
        }
    }

    /**
     * Show view post users
     *
     * @param  Request $request
     * @return Response
     */
    public function viewedUsersList(Request $request,$post_id)
    {

        $html_result='';

        $viewed_users=View::where('post_id', $post_id)
                ->with('users')
                ->limit(env('DEFAULT_HOME_PAGE_VIEWED_USERS_LIMIT'))
                ->orderBy('created_at','desc')
                ->selectRaw('`views`.*,(select count(*) from `users` inner join `views` on `users`.`id` = `views`.`user_id` where  `views`.`post_id` = '.$post_id.' and `users`.`deleted_at` is null) as `users_count`')
                ->get();
                //->toSql();


        //dd($viewed_users);


        if(!empty($viewed_users)){

            $view = \View::make('frontend.includes.posts.viewed_list',compact('viewed_users'));
            $html_result = $view->render();
        }else{
            $html_result = 'users not found ';
        }

        return response()->json(['status' => 200, 'message' => 'success','html_result'=>$html_result]);
    }
}
