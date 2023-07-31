<?php

namespace App\Observers\Rating;

use App\Models\Rating\Rating;
use App\Notifications\Rating\StartedNotification;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;

class RatingObserver
{
    public function updated(Rating $rating): void
    {
        if ( ! $rating->isDirty('status')) {
            return;
        }

        if ( ! $rating->launched_at && $rating->status === 'in progress') {
            $this->notify($rating);

            $rating->launched_at = now();
            $rating->save();
        }
    }

    private function notify(Rating $rating): void
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
                        $client->employee->notify(new StartedNotification(employee: $template->employee, rating: $rating));
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
