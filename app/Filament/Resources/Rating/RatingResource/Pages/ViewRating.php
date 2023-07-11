<?php

namespace App\Filament\Resources\Rating\RatingResource\Pages;

use App\Filament\Resources\Rating\RatingResource;
use App\Forms\Components\TaskList;
use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\ResultClient;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ViewRating extends ViewRecord
{
    protected static string $resource = RatingResource::class;

    protected function form(Form $form): Form
    {
        return $form->schema([
            Card::make()
                ->schema([
                    TaskList::make()
                        ->label('Прогресс')
                        ->headers(['Оцениваемый', 'Оценивающий', 'Статус'])
                        ->items(
                            function (Model|null $record, Closure $get) {
                                $matrixTemplates = $record?->matrixTemplates()
                                    ->with('clients.employee', 'employee')
                                    ->get()
                                    ->flatMap(function (MatrixTemplate $template) use ($record) {
                                        $resultClients = ResultClient::whereHas('result', function (Builder $query) use ($record, $template) {
                                            $query->where('id', $record->id)
                                                ->where('company_employee_id', $template->company_employee_id);
                                        })
                                            ->get();

                                        return $template->clients->map(function (MatrixTemplateClient $client) use ($template, $resultClients) {
                                            return [
                                                $template->employee->full_name,
                                                $client->employee->full_name,
                                                $resultClients->contains(function (ResultClient $resultClient) use ($client) {
                                                    return $resultClient->company_employee_id === $client->employee->id;
                                                })
                                            ];
                                        });
                                    })
                                    ->toArray();

                                return $matrixTemplates;
                            })
                        ->columnSpanFull()
                ])
        ]);
    }
}
