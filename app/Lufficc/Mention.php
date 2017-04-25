<?php
namespace Lufficc;

use App\Comment;
use App\Notifications\Frontend\Comment\MentionedInComment;

class Mention
{
    public $content_original;
    public $content_parsed;

    public function replace()
    {
        $this->content_parsed = $this->content_original;
        foreach (getMentionedUsers($this->content_original) as $user) {
            $search = '@' . $user->username;
            $place = '[' . $search . '](' . route('frontend.auth.user.profile', $user->username) . ')';
            $this->content_parsed = str_replace($search, $place, $this->content_parsed);
        }
    }

    public function parse($content)
    {
        $this->content_original = $content;
        $this->replace();
        return $this->content_parsed;
    }

    public function mentionUsers(Comment $comment, $users, $raw_content)
    {
        foreach ($users as $user) {
            //if (!isAdmin($users)) {
                $user->notify(new MentionedInComment($comment, $raw_content));
            //}
        }
    }
}