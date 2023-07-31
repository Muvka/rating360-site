<?php

namespace App\Notifications\Rating;

use App\Models\Company\Employee;
use App\Settings\AppGeneralSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StartedNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(Employee $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(Employee $notifiable): MailMessage
    {
        $url = 'https://edu.zhcom.ru/my/?redirect=threesixo';

        return (new MailMessage)
            ->subject('Новая оценка')
            ->greeting('Уважаемый '.$notifiable->full_name.'!')
            ->line(app(AppGeneralSettings::class)->notification_rating_start)
            ->action('Перейти', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(Employee $notifiable): array
    {
        return [
            //
        ];
    }
}
