<?php

namespace App\Notifications\Shared;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFaqQuestionNotification extends Notification
{
    public function __construct(
        private readonly string $fullName,
        private readonly string $question,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.shared.faq_question.subject'))
            ->greeting(__('notifications.shared.faq_question.greetings', ['employee' => $this->fullName]))
            ->line($this->question);
    }
}
