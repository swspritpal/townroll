<?php

namespace App\Http\Controllers\Frontend;

use App\Comment;
use App\Repositories\Frontend\Access\Comment\CommentRepository;
use App\Http\Requests;
use Gate;
use Illuminate\Http\Request;
use XblogConfig;
use App\Models\Access\User\User;
use App\Post;

use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function edit(Comment $comment)
    {
        return view('comment.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->checkPolicy('manager', $comment);

        if ($this->commentRepository->update($request->get('content'), $comment)) {
            $redirect = request('redirect');
            if ($redirect)
                return redirect($redirect)->with('success', '修改成功');
            return back()->with('success', '修改成功');
        }
        return back()->withErrors('修改失败');
    }

    public function store(Request $request)
    {
        if (!$request->get('content')) {
            return response()->json(
                ['status' => 500, 'msg' => 'Comment content must not be empty !']
            );
        }

        if ($comment = $this->commentRepository->create($request))
            return response()->json(['status' => 200, 'msg' => 'success']);
        return response()->json(['status' => 500, 'msg' => 'your comment was not posted. Please try again.']);
    }


    public function show(Request $request, $commentable_id)
    {
        $commentable_type = $request->get('commentable_type');
        $last_comment_id = $request->get('last_comment_id');

        $comments = $this->commentRepository->getByCommentable($commentable_type, $commentable_id,$last_comment_id);
        
        $post_model=Post::where('id', $commentable_id)->select(['user_id'])->withCount('comments')->firstOrFail();
        $post_user_id=$post_model->user_id;

        $comments_count=$post_model->comments_count;

        $last_comment=$comments->last()->id;

        $view = \View::make('frontend.comment.show',compact('comments', 'commentable','post_user_id'));
        $html_result = $view->render();

        return response()->json(['status' => 200, 'msg' => 'success','comments_count'=>$comments_count,'last_comment'=>$last_comment,'html_result'=>$html_result]);
    }

    public function destroy($comment_id)
    {
        if (request('force') == 'true') {
            $comment = Comment::withTrashed()->findOrFail($comment_id);
        } else {
            $comment = Comment::findOrFail($comment_id);
        }

        //$this->checkPolicy('manager', $comment);

        if ($this->commentRepository->delete($comment, request('force') == 'true')) {
            return back()->with('success', 'comment delete successfully.');
        }
        return back()->withErrors('There was some error, Please try again.');
    }
}
