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
        $text = app(AppGeneralSettings::class)->notification_rating_start;

        $mailMessage = (new MailMessage)
            ->subject('Оценка 360')
            ->greeting('Добрый день, '.$notifiable->full_name.'!');

        if ($text) {
            $lines = explode("\n", $text);

            if ($lines) {
                foreach ($lines as $line) {
                    $mailMessage->line($line);
                }
            }
        }

        $mailMessage->action('Перейти', $url);

        return $mailMessage;
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
