<?php

namespace App\Observers\Rating;

use App\Mail\Rating\RatingLaunchMail;
use App\Models\Rating\Rating;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class RatingObserver
{
    public function updated(Rating $rating): void
    {
        if ( ! $rating->isDirty('status')) {
            return;
        }

        if ( ! $rating->launched_at && $rating->status === 'in progress') {
            $this->sendEmails($rating);

            $rating->launched_at = now();
            $rating->save();
        }
    }

    private function sendEmails(Rating $rating)
    {
        if (empty($rating->matrix->templates)) {
            return;
        }

        foreach ($rating->matrix->templates as $template) {
            $recipients = [$template->employee->user];
            $failedSending = [];

            if ($template->employee->directManager) {
                $recipients[] = $template->employee->directManager->user;
            }

            if ($template->employee->functionalManager) {
                $recipients[] = $template->employee->functionalManager->user;
            }

            if ($template->clients->isNotEmpty()) {
                foreach ($template->clients as $client) {
                    $recipients[] = $client->employeeUser;
                }
            }

            foreach ($recipients as $index => $recipient) {
                try {
                    Mail::to($recipient->email)
                        ->send(new RatingLaunchMail($template->employee, $index === 0));
                } catch (\Throwable $exception) {
                    $failedSending[] = sprintf('%s (**%s**)', $recipient->fullName, $recipient->email);
                }
            }

            if ( ! empty($failedSending)) {
                Notification::make()
                    ->title('Внимание')
                    ->body('Не удалось отправить уведомление следующим сотрудникам - '.Arr::join($failedSending, ', '))
                    ->danger()
                    ->persistent()
                    ->actions([
                        Action::make('close')
                            ->label('Закрыть')
                            ->button()
                            ->close(),
                    ])
                    ->send();
            }
        }
    }
}
