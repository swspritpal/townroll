<?php

namespace App\Notifications\Frontend\Comment;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Frontend\StreamChannel;

class MentionedInComment extends BaseNotification
{
    use Queueable;
    protected $comment;
    protected $raw_content;

    public function __construct(Comment $comment, $raw_content)
    {
        $this->comment = $comment;
        $this->raw_content = $raw_content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->enableMail()) {
            return ['database', 'mail'];
        }
        return [StreamChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /*$data = $this->comment->getCommentableData();
        return (new MailMessage)
            ->success()
            ->greeting($notifiable->name)
            ->replyTo($notifiable->email)
            ->subject('You have new comment')
            ->line($this->comment->username . ' in ' . $data['type'] . ':' . $data['title'] . ' describe:')
            ->line($this->raw_content)
            ->action('view ', $data['url']);*/
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->comment->toArray();
    }

     /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toStreamActivity($notifiable)
    {
        $data = $this->comment;

        // Comment posting Notification to notify mention users
        $notifyTo=$notifiable->id;

        // when some other user comment on post then notify to Auther 
        if($notifyTo != \Auth::id()){
            $user_notification=\FeedManager::getUserFeed(\Auth::id());

            // Push notification for Stream about the comment action
            $data = [
                "actor"=>"\App\Models\Access\User\User:".\Auth::user()->id,
                "verb"=>"mention_in_comment",
                "object"=>$data->commentable_type.":".$data->commentable_id,
                "foreign_id"=>"\App\Comment:".$data->id,
                "is_read" => false,
                "is_seen" => false,
                'to' => ['notification:'.$notifyTo],
            ];

            return $user_notification->addActivity($data);
        }
    }
}
