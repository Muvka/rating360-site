<?php

namespace App\Filament\Resources\User\RatingTemplateResource\Pages;

use App\Filament\Resources\User\RatingTemplateResource;
use App\Imports\User\RatingTemplateImport;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListRatingTemplates extends ListRecords
{
    protected static string $resource = RatingTemplateResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('import')->label('Импорт')->color('secondary')
                ->action(function (array $data): void {
                    Excel::import(new RatingTemplateImport($data['name']), $data['file'], 'public');
                })
                ->modalButton('Импортировать')
                ->form([
                    FileUpload::make('file')
                        ->label('Таблица')
                        ->disk('public')
                        ->acceptedFileTypes([
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->required(),
                    TextInput::make('name')
                        ->label('Название')
                        ->placeholder('Шаблон общий')
                        ->hidden(fn(Closure $get) => $get('file') === [])
                        ->maxLength(128)
                        ->required(),
                ]),
            Actions\CreateAction::make(),
        ];
    }
}
