<?php
namespace Lufficc\Post;

use App\Post;
use App\View;

trait PostHelper
{
    /**
     * onPostShowing, increase view counter.
     *
     * @param Post $post
     */
    public function onPostShowing(Post $post)
    {
        $is_viewed_already=(bool) View::where('user_id', \Auth::id())
                            ->where('post_id', $this->id)
                            ->first();

        $user = auth()->user();
        if($is_viewed_already == false){
            \Auth::user()->views()->attach($post->id);
        }
        
          /*  $unreadNotifications = $user->unreadNotifications;
            foreach ($unreadNotifications as $notifications) {
                $comment = $notifications->data;
                if ($comment['commentable_type'] == 'App\Post' && $comment['commentable_id'] == $post->id) {
                    $notifications->markAsRead();
                }
            }*/
    }
}