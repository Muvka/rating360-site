<?php

namespace App\Filament\Resources\Rating\TemplateResource\Pages;

use App\Filament\Resources\Rating\TemplateResource;
use App\Imports\Rating\TemplateImport;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListTemplates extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('import')->label('Импорт')->color('secondary')
                ->action(function (array $data): void {
                    Excel::import(new TemplateImport($data['name']), $data['file'], 'public');
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
