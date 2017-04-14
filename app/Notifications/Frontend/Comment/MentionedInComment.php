<?php

namespace App\Notifications\Frontend\Comment;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = $this->comment->getCommentableData();
        return (new MailMessage)
            ->success()
            ->greeting($notifiable->name)
            ->replyTo($notifiable->email)
            ->subject('You have new comment')
            ->line($this->comment->username . ' in ' . $data['type'] . ':' . $data['title'] . ' describe:')
            ->line($this->raw_content)
            ->action('view ', $data['url']);
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
}
