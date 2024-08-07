<?php

namespace App\Services\Rating;

use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Notifications\Rating\StartedNotification;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class AssessmentStartNotifyService
{
    public function notify(Rating $rating): void
    {
        if (empty($rating->matrix->templates)) {
            return;
        }

        $clients = MatrixTemplateClient::select('company_employee_id')
            ->with('employee')
            ->whereHas('template.matrix.ratings', function (Builder $query) use ($rating) {
                $query->where('id', $rating->id);
            })
            ->distinct()
            ->get();
        $failedSending = [];

        foreach ($clients as $client) {
            try {
                if (! $client->employee) {
                    continue;
                }

                $client->employee->notify(new StartedNotification());
            } catch (\Throwable $exception) {
                $failedSending[] = sprintf('%s (**%s**)', $client->employee->full_name, $client->employee->email);
            }
        }

        if (! empty($failedSending)) {
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
