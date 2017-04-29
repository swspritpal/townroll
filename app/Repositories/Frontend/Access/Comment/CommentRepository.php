<?php
namespace App\Repositories\Frontend\Access\Comment;

use App\Comment;
use Illuminate\Http\Request;
use Lufficc\Exception\CommentNotAllowedException;
use Lufficc\MarkDownParser;
use Lufficc\Mention;
use App\Repositories\Frontend\Repository;

/**
 * Class CommentRepository
 * @package App\Http\Repository
 */
class CommentRepository extends Repository
{
    static $tag = 'comment';
    protected $markdownParser;
    protected $mention;

    /**
     * PostRepository constructor.
     * @param Mention $mention
     * @param MarkDownParser $markDownParser
     */
    public function __construct(Mention $mention, MarkDownParser $markDownParser)
    {
        $this->mention = $mention;
        $this->markdownParser = $markDownParser;
    }

    public function model()
    {
        return app(Comment::class);
    }

    public function count()
    {
        $count = $this->remember($this->tag() . '.count', function () {
            return $this->model()->withTrashed()->count();
        });
        return $count;
    }

    private function getCacheKey($commentable_type, $commentable_id)
    {
        return $commentable_type . '.' . $commentable_id . 'comments';
    }

    public function getByCommentable($commentable_type, $commentable_id,$last_comment_id)
    {
        $comments = $this->remember($this->getCacheKey($commentable_type, $commentable_id,$last_comment_id), function () use ($commentable_type, $commentable_id,$last_comment_id) {
            $commentable = app($commentable_type)->where('id', $commentable_id)->select(['id'])->firstOrFail();
            return $commentable->comments()->with(['user'])->where('id','>', $last_comment_id)->orderBy('id', 'asc')->get();
        });
        return $comments;
    }

    public function getAll($page = 20)
    {
        $comments = $this->remember('comment.page.' . $page . '' . request()->get('page', 1), function () use ($page) {
            return Comment::withoutGlobalScopes()->orderBy('created_at', 'desc')->paginate($page);
        });
        return $comments;
    }

    public function create(Request $request)
    {
        $this->clearCache();

        $comment = new Comment();
        $commentable_id = $request->get('commentable_id');
        $commentable = app($request->get('commentable_type'))->where('id', $commentable_id)->firstOrFail();

        /*if (!$commentable->isShownComment() || !$commentable->allowComment()) {
            throw new CommentNotAllowedException;
        }*/


        $content = $request->get('content');
        $comment->ip_id = $request->ip();
        $comment->user_id = access()->user()->id;
        $comment->content = $this->mention->parse($content);
        $comment->html_content = $this->markdownParser->parse($comment->content);
        $result = $commentable->comments()->save($comment);


        // Comment posting Notification
        $notifyTo=$commentable->user_id;

        // when some other user comment on post then notify to Auther 
        if($notifyTo != \Auth::id()){
            $user_notification=\FeedManager::getUserFeed(\Auth::id());

            // Push notification for Stream about the comment action
            $data = [
                "actor"=>"\App\Models\Access\User\User:".\Auth::user()->id,
                "verb"=>"comment",
                "object"=>$request->get('commentable_type').":".$commentable->id,
                "foreign_id"=>"\App\Comment:".$result->id,
                "is_read" => false,
                "is_seen" => false,
                'to' => ['notification:'.$notifyTo],
            ];

            $user_notification->addActivity($data);
        }

        /**
         * mention user after comment saved
         */
        $this->mention->mentionUsers($comment, getMentionedUsers($content), $content);

        return $result;
    }

    public function update($content, $comment)
    {
        $comment->content = $this->mention->parse($content);
        $comment->html_content = $this->markdownParser->parse($comment->content);
        $result = $comment->save();
        if ($result)
            $this->clearCache();
        return $result;
    }

    public function delete(Comment $comment, $force = false)
    {
        $this->clearCache();        
        if ($force)
            return $comment->forceDelete();
        return $comment->delete();
    }

    public function tag()
    {
        return CommentRepository::$tag;
    }

}