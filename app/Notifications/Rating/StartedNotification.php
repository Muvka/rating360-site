<?php

namespace App\Notifications\Rating;

use App\Models\Company\Employee;
use App\Models\Rating\Rating;
use App\Settings\AppGeneralSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class StartedNotification extends Notification
{
    use Queueable;

    private Employee $employee;

    private Rating $rating;

    /**
     * Create a new notification instance.
     */
    public function __construct(Employee $employee, Rating $rating)
    {
        $this->employee = $employee;
        $this->rating = $rating;
    }

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
        $text = Str::replace(':employee', $this->employee->full_name, app(AppGeneralSettings::class)->notification_rating_start);
        $url = route('client.statistic.results.create', [
            'rating' => $this->rating,
            'employee' => $this->employee,
        ]);

        return (new MailMessage)
            ->subject('Новая оценка')
            ->greeting('Уважаемый '.$notifiable->full_name.'!')
            ->line($text)
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
