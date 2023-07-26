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

    private function sendEmails(Rating $rating): void
    {
        if (empty($rating->matrix->templates)) {
            return;
        }

        foreach ($rating->matrix->templates as $template) {
            $failedSending = [];

            if ($template->clients->isNotEmpty()) {
                $template->clients->load('employee');

                foreach ($template->clients as $client) {
                    try {
                        Mail::to($client->employee->email)
                            ->send(new RatingLaunchMail($template->employee, $template->employee->id === $client->employee->id));
                    } catch (\Throwable $exception) {
                        $failedSending[] = sprintf('%s (**%s**)', $client->employee->full_name, $client->employee->email);
                    }
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
