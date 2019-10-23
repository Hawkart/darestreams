<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyFollowersAboutStream extends Notification implements ShouldQueue
{
    use Queueable,SerializesModels;

    public $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        Log::info('NotifyFollowersAboutStream', ['details' => $this->details, 'file' => __FILE__, 'line' => __LINE__]);

        $message =  (new MailMessage)
            ->subject($this->details['subject'])
            ->greeting($this->details['greeting'])
            ->line($this->details['body'])
            ->action($this->details['actionText'], $this->details['actionURL']);

        Log::info('NotifyFollowersAboutStream', ['message' => $message, 'file' => __FILE__, 'line' => __LINE__]);

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}